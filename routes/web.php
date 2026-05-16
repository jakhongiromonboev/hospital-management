<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ChatController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Language switcher
Route::get('/locale/{lang}', function (string $lang) {
    if (in_array($lang, ['ko', 'en'], true)) {
        session(['locale' => $lang]);
    }
    return back();
})->name('locale.switch');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // ─── Admin Routes ───
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/doctors', [AdminController::class, 'doctors'])->name('doctors');
        Route::get('/doctors/create', [AdminController::class, 'createDoctor'])->name('doctors.create');
        Route::post('/doctors', [AdminController::class, 'storeDoctor'])->name('doctors.store');
        Route::get('/doctors/{doctor}/edit', [AdminController::class, 'editDoctor'])->name('doctors.edit');
        Route::put('/doctors/{doctor}', [AdminController::class, 'updateDoctor'])->name('doctors.update');
        Route::delete('/doctors/{doctor}', [AdminController::class, 'destroyDoctor'])->name('doctors.destroy');
        Route::get('/patients', [AdminController::class, 'patients'])->name('patients');
        Route::get('/patients/{patient}', [AdminController::class, 'patientDetail'])->name('patients.detail');
        Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments');
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    });

    // ─── Doctor Routes ───
    Route::middleware(['role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
        Route::get('/appointments', [DoctorController::class, 'appointments'])->name('appointments');
        Route::put('/appointments/{appointment}/status', [DoctorController::class, 'updateAppointmentStatus'])->name('appointments.updateStatus');
        Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
        Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
        Route::put('/schedule/{schedule}', [ScheduleController::class, 'update'])->name('schedule.update');
        Route::delete('/schedule/{schedule}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
        Route::get('/patients', [DoctorController::class, 'patients'])->name('patients');
        Route::get('/patients/{patient}', [DoctorController::class, 'patientDetail'])->name('patients.detail');
        Route::get('/prescriptions', [PrescriptionController::class, 'doctorIndex'])->name('prescriptions');
        Route::get('/prescriptions/create/{patient?}', [PrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
    });

    // ─── Patient Routes ───
    Route::middleware(['role:patient'])->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
        Route::get('/pain-map', [PatientController::class, 'painMap'])->name('pain-map');
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.cancel');
        Route::get('/prescriptions', [PrescriptionController::class, 'patientIndex'])->name('prescriptions');
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
        Route::get('/payments/{payment}/pay', [PaymentController::class, 'pay'])->name('payments.pay');
        Route::post('/payments/{payment}/process', [PaymentController::class, 'process'])->name('payments.process');
    });

    // ─── Shared Routes (PDF exports) ───
    Route::get('/prescriptions/{prescription}/pdf', [PrescriptionController::class, 'exportPdf'])->name('prescriptions.pdf');
    Route::get('/payments/{payment}/invoice', [PaymentController::class, 'invoice'])->name('payments.invoice');

    // ─── AI Chat ───
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/clear', [ChatController::class, 'clearHistory'])->name('chat.clear');
});
