@extends('layouts.app')
@section('page-title', '내 결제')

@section('content')
<div class="card">
    <div class="card-header py-3">결제 내역</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>청구번호</th><th>내용</th><th>금액</th><th>상태</th><th>날짜</th><th>작업</th></tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td class="fw-semibold">{{ $payment->invoice_number }}</td>
                    <td>{{ $payment->description }}</td>
                    <td>${{ number_format($payment->amount, 2) }}</td>
                    <td><span class="badge badge-{{ $payment->status }}">{{ $t['payment_status'][$payment->status] ?? $payment->status }}</span></td>
                    <td>{{ $payment->paid_at ? ($payment->paid_at->year.'년 '.$payment->paid_at->month.'월 '.$payment->paid_at->day.'일') : '-' }}</td>
                    <td>
                        @if($payment->status === 'pending')
                            <a href="{{ route('patient.payments.pay', $payment) }}" class="btn btn-sm btn-primary">결제하기</a>
                        @endif
                        @if($payment->status === 'paid')
                            <a href="{{ route('payments.invoice', $payment) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> 영수증
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">결제 내역이 없습니다</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $payments->links() }}</div>
@endsection
