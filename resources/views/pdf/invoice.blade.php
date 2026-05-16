<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>청구서 {{ $payment->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 3px solid #0d6efd; padding-bottom: 15px; margin-bottom: 25px; }
        .header h1 { color: #0d6efd; margin: 0; font-size: 24px; }
        .header p { margin: 3px 0; color: #666; font-size: 12px; }
        .invoice-title { text-align: center; font-size: 22px; font-weight: bold; margin: 15px 0; }
        .info-table { width: 100%; margin-bottom: 25px; }
        .info-table td { vertical-align: top; width: 50%; padding: 5px; }
        .info-box { background: #f8f9fa; padding: 12px; border-radius: 6px; }
        .info-box h4 { margin: 0 0 8px; color: #0d6efd; text-transform: uppercase; font-size: 13px; }
        .info-box p { margin: 3px 0; font-size: 12px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.items th { background: #0d6efd; color: white; padding: 10px; text-align: left; }
        table.items td { padding: 10px; border-bottom: 1px solid #dee2e6; }
        .total-row { background: #f8f9fa; font-weight: bold; font-size: 15px; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 15px; font-size: 11px; font-weight: bold; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .footer { text-align: center; margin-top: 35px; padding-top: 15px; border-top: 2px solid #dee2e6; color: #666; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>병원 관리 시스템</h1>
        <p>서울특별시 ○○구 의료로 123</p>
        <p>전화: 02-1234-5678 | 이메일: billing@hospital.com</p>
    </div>

    <div class="invoice-title">청구서 (INVOICE)</div>
    <p style="text-align: center; color: #666; font-size: 12px;">{{ $payment->invoice_number }} | 발행일: {{ $payment->created_at->year }}년 {{ $payment->created_at->month }}월 {{ $payment->created_at->day }}일</p>

    <table class="info-table">
        <tr>
            <td>
                <div class="info-box">
                    <h4>청구 대상</h4>
                    <p><strong>{{ $payment->patient->name }}</strong></p>
                    <p>{{ $payment->patient->email }}</p>
                    <p>{{ $payment->patient->phone ?? '' }}</p>
                    <p>{{ $payment->patient->address ?? '' }}</p>
                </div>
            </td>
            <td>
                <div class="info-box">
                    <h4>청구 정보</h4>
                    <p><strong>청구번호:</strong> {{ $payment->invoice_number }}</p>
                    <p><strong>발행일:</strong> {{ $payment->created_at->year }}년 {{ $payment->created_at->month }}월 {{ $payment->created_at->day }}일</p>
                    <p><strong>상태:</strong>
                        <span class="status-badge {{ $payment->status === 'paid' ? 'status-paid' : 'status-pending' }}">
                            {{ $t['payment_status'][$payment->status] ?? $payment->status }}
                        </span>
                    </p>
                    @if($payment->paid_at)
                        <p><strong>결제일:</strong> {{ $payment->paid_at->year }}년 {{ $payment->paid_at->month }}월 {{ $payment->paid_at->day }}일 {{ $payment->paid_at->format('H:i') }}</p>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr><th>#</th><th>내용</th><th style="text-align: right;">금액</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $payment->description }}</td>
                <td style="text-align: right;">${{ number_format($payment->amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="2" style="text-align: right;"><strong>합계:</strong></td>
                <td style="text-align: right;"><strong>${{ number_format($payment->amount, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    @if($payment->payment_method)
        <p><strong>결제 수단:</strong> {{ $t['payment_method'][$payment->payment_method] ?? $payment->payment_method }}</p>
    @endif
    @if($payment->transaction_id)
        <p><strong>거래번호:</strong> {{ $payment->transaction_id }}</p>
    @endif

    <div class="footer">
        <p>저희 병원을 이용해 주셔서 감사합니다.</p>
        <p>본 문서는 시스템에서 자동 생성되었습니다. | 병원 관리 시스템</p>
        <p>생성 시각: {{ now()->year }}년 {{ now()->month }}월 {{ now()->day }}일 {{ now()->format('H:i') }}</p>
    </div>
</body>
</html>
