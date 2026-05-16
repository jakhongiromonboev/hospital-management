@extends('layouts.app')
@section('page-title', '결제')

@section('content')
<div class="card">
    <div class="card-header py-3">전체 결제</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>청구번호</th><th>환자</th><th>금액</th><th>상태</th><th>수단</th><th>날짜</th></tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td class="fw-semibold">{{ $payment->invoice_number }}</td>
                    <td>{{ $payment->patient->name }}</td>
                    <td>${{ number_format($payment->amount, 2) }}</td>
                    <td><span class="badge badge-{{ $payment->status }}">{{ $t['payment_status'][$payment->status] ?? ucfirst($payment->status) }}</span></td>
                    <td>{{ $payment->payment_method ? ($t['payment_method'][$payment->payment_method] ?? $payment->payment_method) : '-' }}</td>
                    <td>{{ $payment->paid_at ? ($payment->paid_at->year.'년 '.$payment->paid_at->month.'월 '.$payment->paid_at->day.'일') : '-' }}</td>
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
