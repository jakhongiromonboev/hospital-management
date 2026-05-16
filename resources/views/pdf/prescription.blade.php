<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>처방전 #{{ $prescription->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 3px solid #0d6efd; padding-bottom: 15px; margin-bottom: 25px; }
        .header h1 { color: #0d6efd; margin: 0; font-size: 24px; }
        .header p { margin: 3px 0; color: #666; font-size: 12px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { vertical-align: top; width: 50%; padding: 5px; }
        .info-box { background: #f8f9fa; padding: 12px; border-radius: 6px; }
        .info-box h4 { margin: 0 0 8px; color: #0d6efd; font-size: 13px; text-transform: uppercase; }
        .info-box p { margin: 3px 0; font-size: 12px; }
        .diagnosis { background: #e8f4fd; padding: 12px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #0d6efd; }
        .diagnosis h3 { margin: 0 0 6px; color: #0d6efd; font-size: 14px; }
        table.meds { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.meds th { background: #0d6efd; color: white; padding: 8px; text-align: left; font-size: 12px; }
        table.meds td { padding: 8px; border-bottom: 1px solid #dee2e6; font-size: 12px; }
        table.meds tr:nth-child(even) { background: #f8f9fa; }
        .signature { margin-top: 40px; text-align: right; }
        .signature .line { border-top: 1px solid #333; width: 180px; display: inline-block; margin-top: 30px; }
        .footer { text-align: center; margin-top: 30px; padding-top: 15px; border-top: 2px solid #dee2e6; color: #666; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>병원 관리 시스템</h1>
        <p>서울특별시 ○○구 의료로 123</p>
        <p>전화: 02-1234-5678 | 이메일: info@hospital.com</p>
    </div>

    <h2 style="text-align: center; color: #333; font-size: 18px;">처방전</h2>
    <p style="text-align: center; color: #666; font-size: 12px;">처방전 번호 #{{ $prescription->id }} | 발행일: {{ $prescription->created_at->year }}년 {{ $prescription->created_at->month }}월 {{ $prescription->created_at->day }}일</p>

    <table class="info-table">
        <tr>
            <td>
                <div class="info-box">
                    <h4>환자 정보</h4>
                    <p><strong>이름:</strong> {{ $prescription->patient->name }}</p>
                    <p><strong>이메일:</strong> {{ $prescription->patient->email }}</p>
                    <p><strong>전화:</strong> {{ $prescription->patient->phone ?? '없음' }}</p>
                    <p><strong>혈액형:</strong> {{ $prescription->patient->blood_group ?? '없음' }}</p>
                </div>
            </td>
            <td>
                <div class="info-box">
                    <h4>의사 정보</h4>
                    <p><strong>이름:</strong> {{ $prescription->doctor->name }} 의사</p>
                    <p><strong>전문과목:</strong> {{ $prescription->doctor->specialization ?? '없음' }}</p>
                    <p><strong>이메일:</strong> {{ $prescription->doctor->email }}</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="diagnosis">
        <h3>진단</h3>
        <p>{{ $prescription->diagnosis }}</p>
        @if($prescription->notes)
            <p><strong>비고:</strong> {{ $prescription->notes }}</p>
        @endif
    </div>

    <h3 style="font-size: 14px;">처방 약품</h3>
    <table class="meds">
        <thead>
            <tr><th>#</th><th>약품명</th><th>용량</th><th>복용</th><th>기간</th><th>복용법</th></tr>
        </thead>
        <tbody>
            @foreach($prescription->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->medicine_name }}</td>
                <td>{{ $item->dosage }}</td>
                <td>{{ $item->frequency }}</td>
                <td>{{ $item->duration }}</td>
                <td>{{ $item->instructions ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <p>{{ $prescription->doctor->name }} 의사</p>
        <p style="font-size: 12px;">{{ $prescription->doctor->specialization }}</p>
        <div class="line"></div>
        <p style="font-size: 11px;">서명</p>
    </div>

    <div class="footer">
        <p>본 처방전은 시스템에서 자동 생성되었습니다. | 병원 관리 시스템</p>
        <p>생성 시각: {{ now()->year }}년 {{ now()->month }}월 {{ now()->day }}일 {{ now()->format('H:i') }}</p>
    </div>
</body>
</html>
