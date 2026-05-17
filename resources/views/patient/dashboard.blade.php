@extends('layouts.app')
@section('page-title', __('dashboard.patient.page_title'))

@section('content')
@include('partials.weather')

<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="label">{{ __('dashboard.patient.stat_upcoming') }}</div><div class="value">{{ $stats['upcoming_appointments'] }}</div></div>
                <div class="icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-calendar-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="label">{{ __('dashboard.patient.stat_prescriptions') }}</div><div class="value">{{ $stats['total_prescriptions'] }}</div></div>
                <div class="icon bg-success bg-opacity-10 text-success"><i class="bi bi-file-earmark-medical"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="label">{{ __('dashboard.patient.stat_unpaid') }}</div><div class="value">${{ number_format($stats['pending_payments'], 2) }}</div></div>
                <div class="icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-credit-card"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="label">{{ __('dashboard.patient.stat_completed_visits') }}</div><div class="value">{{ $stats['completed_appointments'] }}</div></div>
                <div class="icon bg-info bg-opacity-10 text-info"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">{{ __('dashboard.patient.upcoming_heading') }}</h5>
    <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> {{ __('dashboard.patient.book_appointment') }}</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{{ __('dashboard.patient.th_doctor') }}</th>
                    <th>{{ __('dashboard.patient.th_specialty') }}</th>
                    <th>{{ __('dashboard.patient.th_date') }}</th>
                    <th>{{ __('dashboard.patient.th_time') }}</th>
                    <th>{{ __('dashboard.patient.th_status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($upcoming_appointments as $apt)
                <tr>
                    <td class="fw-semibold">{{ __('dashboard.doctor_name', ['name' => $apt->doctor->name]) }}</td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $apt->doctor->specialization }}</span></td>
                    <td>{{ $apt->appointment_date->format(__('dashboard.date_format')) }}</td>
                    <td>{{ \Carbon\Carbon::parse($apt->appointment_time)->format('H:i') }}</td>
                    <td><span class="badge badge-{{ $apt->status }}">{{ $t['appointment_status'][$apt->status] ?? $apt->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">{{ __('dashboard.patient.no_upcoming') }} <a href="{{ route('patient.appointments.create') }}">{{ __('dashboard.patient.book_now') }}</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
