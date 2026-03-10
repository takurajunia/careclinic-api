<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ClaimController;

// Patients
Route::get('patients/archived', [PatientController::class, 'archived']);
Route::put('patients/{id}/archive', [PatientController::class, 'archive']);
Route::put('patients/{id}/restore', [PatientController::class, 'restore']);
Route::apiResource('patients', PatientController::class);

// Appointments
Route::get('appointments/today', [AppointmentController::class, 'today']);
Route::apiResource('appointments', AppointmentController::class);

// Consultations
Route::apiResource('consultations', ConsultationController::class);

// Claims
Route::get('claims', [ClaimController::class, 'index']);
Route::get('claims/{id}/export-pdf', [ClaimController::class, 'exportPdf']);
Route::get('claims/{id}', [ClaimController::class, 'show']);
Route::put('claims/{id}', [ClaimController::class, 'update']);

// Dashboard
Route::get('dashboard', function () {
    $patientsToday = \App\Models\Appointment::whereDate('date', today())
        ->where('status', 'Completed')
        ->count();

    $upcomingAppointments = \App\Models\Appointment::with('patient')
        ->whereDate('date', '>=', today())
        ->where('status', 'Scheduled')
        ->orderBy('date')
        ->orderBy('time')
        ->take(10)
        ->get();

    return response()->json([
        'patients_seen_today'    => $patientsToday,
        'upcoming_appointments'  => $upcomingAppointments,
    ]);
});