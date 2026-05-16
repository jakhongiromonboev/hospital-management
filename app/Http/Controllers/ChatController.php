<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000'], [
            'message.required' => '메시지를 입력해 주세요.',
            'message.max' => '메시지는 1000자 이하여야 합니다.',
        ]);

        $userMessage = $request->message;
        $user = auth()->user();

        $systemContext = $this->buildSystemContext($user);

        $history = Session::get('chat_history', []);

        $history[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];

        if (count($history) > 20) {
            $history = array_slice($history, -20);
        }

        try {
            $reply = $this->callGemini($systemContext, $history);
        } catch (\Throwable $e) {
            \Log::warning('Chat AI fallback triggered', ['error' => $e->getMessage()]);
            $reply = $this->fallbackResponse($userMessage, $user);
        }

        $history[] = ['role' => 'model', 'parts' => [['text' => $reply]]];
        Session::put('chat_history', $history);

        return response()->json([
            'reply' => $reply,
            'timestamp' => now()->format('H:i'),
        ]);
    }

    public function clearHistory()
    {
        Session::forget('chat_history');

        return response()->json(['status' => 'ok']);
    }

    private function buildSystemContext($user): string
    {
        $context = "당신은 병원 관리 시스템(HMS)에 내장된 AI 어시스턴트입니다. ";
        $context .= "사용자가 묻는 모든 질문에 답할 수 있습니다: 일반 상식, 프로그래밍, 과학, 수학, 역사, 일상 대화 등. ";
        $context .= "병원과 무관한 질문이라고 거절하지 마세요. 먼저 범용 도우미이며, 아래 HMS 정보는 사용자가 예약·의사·결제·처방·본인 데이터를 물을 때만 보조적으로 사용하세요. ";
        $context .= "기본 응답 언어는 한국어입니다. 사용자가 영어·우즈베크어·러시아어 등 다른 언어로 쓰면 그 언어에 맞춰 답하세요. ";
        $context .= "강조는 **굵게** 표기. 이모지는 자연스럽게. 답변은 간결하게, 필요할 때만 자세히 설명하세요. ";
        $context .= "\n\n--- HMS 보조 정보 (관련 있을 때만 사용) ---\n";
        $context .= "현재 사용자: {$user->name} (역할: {$user->role}, 이메일: {$user->email}).\n";

        if ($user->role === 'patient') {
            $upcoming = $user->patientAppointments()
                ->whereDate('appointment_date', '>=', today())
                ->whereIn('status', ['pending', 'confirmed'])
                ->with('doctor')
                ->get();

            $pendingPayments = $user->payments()->where('status', 'pending')->sum('amount');
            $prescriptionCount = $user->patientPrescriptions()->count();

            $context .= "환자 데이터:\n";
            $context .= '- 예정된 예약: ' . $upcoming->count() . "건\n";
            if ($upcoming->isNotEmpty()) {
                foreach ($upcoming as $apt) {
                    $context .= "  * {$apt->doctor->name} 의사 ({$apt->doctor->specialization}) — {$apt->appointment_date->format('Y-m-d')} " . \Carbon\Carbon::parse($apt->appointment_time)->format('H:i') . " — 상태: {$apt->status}\n";
                }
            }
            $context .= "- 미결제 금액 합계: \${$pendingPayments}\n";
            $context .= "- 처방전 수: {$prescriptionCount}건\n";
        } elseif ($user->role === 'doctor') {
            $todayAppts = $user->doctorAppointments()->whereDate('appointment_date', today())->count();
            $pendingAppts = $user->doctorAppointments()->where('status', 'pending')->count();
            $totalPatients = $user->doctorAppointments()->distinct('patient_id')->count('patient_id');

            $context .= "의사 데이터 (전문과목: {$user->specialization}):\n";
            $context .= "- 오늘 예약: {$todayAppts}건\n";
            $context .= "- 승인 대기: {$pendingAppts}건\n";
            $context .= "- 총 환자 수: {$totalPatients}명\n";
        } elseif ($user->role === 'admin') {
            $context .= "관리자 데이터:\n";
            $context .= '- 전체 의사: ' . \App\Models\User::where('role', 'doctor')->count() . "명\n";
            $context .= '- 전체 환자: ' . \App\Models\User::where('role', 'patient')->count() . "명\n";
            $context .= '- 전체 예약: ' . \App\Models\Appointment::count() . "건\n";
            $context .= '- 매출(결제완료): $' . number_format(\App\Models\Payment::where('status', 'paid')->sum('amount'), 2) . "\n";
        }

        $doctors = \App\Models\User::where('role', 'doctor')->get(['name', 'specialization']);
        $context .= "\n등록된 의사:\n";
        foreach ($doctors as $d) {
            $context .= "- {$d->name} 의사 — {$d->specialization}\n";
        }

        $context .= "\n--- HMS 정보 끝 ---\n";
        $context .= '중요한 증상이나 응급 상황이면 실제 의료기관 방문 또는 응급(한국 119) 안내를 하세요. 그 외 주제는 자유롭게 답변하세요.';

        return $context;
    }

    private function callGemini(string $systemContext, array $history): string
    {
        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            throw new \Exception('Gemini API key not configured');
        }

        $response = Http::timeout(30)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
            [
                'system_instruction' => [
                    'parts' => [['text' => $systemContext]],
                ],
                'contents' => $history,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 500,
                    'topP' => 0.9,
                ],
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
                ],
            ]
        );

        if ($response->successful()) {
            $data = $response->json();

            return $data['candidates'][0]['content']['parts'][0]['text'] ?? '죄송합니다. 응답을 생성하지 못했습니다.';
        }

        throw new \Exception('Gemini API error: ' . $response->status() . ' | body: ' . $response->body());
    }

    private function fallbackResponse(string $message, $user): string
    {
        $msg = strtolower($message);

        $hasKoreanHello = str_contains($message, '안녕') || str_contains($message, '하이');
        if ($hasKoreanHello || preg_match('/\b(hello|hi|hey|salom|assalom)\b/i', $msg)) {
            return "안녕하세요, {$user->name}님! 👋 HMS AI 어시스턴트입니다.\n\n⚠️ AI 서비스가 일시적으로 사용할 수 없습니다. 기본 안내만 가능합니다.\n\n**도움** 또는 **help**를 입력해 보세요.";
        }

        if (str_contains($message, '도움') || preg_match('/\b(help|yordam)\b/i', $msg)) {
            return "🤖 **사용 가능한 안내:**\n\n• **예약** / appointment — 예약 정보\n• **의사** / doctor — 의사 목록\n• **결제** / payment — 결제 안내\n• **처방** / prescription — 처방전\n• **응급** / emergency — 응급 연락처\n\n⚠️ 전체 AI 응답은 API 연결이 복구되면 다시 이용할 수 있습니다.";
        }

        if (str_contains($message, '예약') || preg_match('/\b(appointment|uchrashuv)\b/i', $msg)) {
            if ($user->role === 'patient') {
                $count = $user->patientAppointments()->whereDate('appointment_date', '>=', today())->whereIn('status', ['pending', 'confirmed'])->count();

                return "📅 다가오는 예약이 **{$count}**건 있습니다.\n\n자세한 내용은 **예약** 메뉴에서 확인하세요.";
            }
        }

        if (str_contains($message, '의사') || preg_match('/\b(doctor|shifokor)\b/i', $msg)) {
            $doctors = \App\Models\User::where('role', 'doctor')->get();
            $list = $doctors->map(fn ($d) => "• **{$d->name}** 의사 — {$d->specialization}")->join("\n");

            return "👨‍⚕️ 등록된 의사:\n\n{$list}";
        }

        if (str_contains($message, '응급') || preg_match('/\b(emergency|tez yordam)\b/i', $msg)) {
            return "🚨 **응급:** 한국 **119** (응급·구급)\n🏥 응급실: 24시간 운영(일반 안내)";
        }

        return "⚠️ AI 서비스가 일시적으로 사용할 수 없습니다.\n\n기본 명령: **도움**, **예약**, **의사**, **결제**, **응급**\n\n연결이 복구되면 전체 채팅이 다시 가능합니다.";
    }
}
