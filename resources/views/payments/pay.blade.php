@extends('layouts.app')
@section('page-title', '결제')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header py-3">결제 정보</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">청구번호:</span>
                    <span class="fw-semibold">{{ $payment->invoice_number }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">내용:</span>
                    <span>{{ $payment->description }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="fw-bold fs-5">결제 금액:</span>
                    <span class="fw-bold fs-5 text-primary">${{ number_format($payment->amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header py-3">결제 수단</div>
            <div class="card-body">
                <form method="POST" action="{{ route('patient.payments.process', $payment) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold">결제 수단 선택 *</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">선택</option>
                            <option value="cash">{{ $t['payment_method']['cash'] }}</option>
                            <option value="credit_card">{{ $t['payment_method']['credit_card'] }}</option>
                            <option value="debit_card">{{ $t['payment_method']['debit_card'] }}</option>
                            <option value="bank_transfer">{{ $t['payment_method']['bank_transfer'] }}</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">${{ number_format($payment->amount, 2) }} 결제</button>
                    </div>
                </form>
                <a href="{{ route('patient.payments') }}" class="btn btn-light w-100 mt-2">돌아가기</a>
            </div>
        </div>
    </div>
</div>
@endsection
