<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Appointment;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConsultationController extends Controller
{
    public function index()
    {
        return response()->json(Consultation::with(['patient', 'appointment'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'           => 'required|exists:patients,id',
            'appointment_id'       => 'required|exists:appointments,id',
            'blood_pressure'       => 'nullable|string',
            'temperature'          => 'nullable|numeric',
            'weight'               => 'nullable|numeric',
            'pulse'                => 'nullable|integer',
            'diagnosis'            => 'required|string',
            'prescribed_treatment' => 'required|string',
            'notes'                => 'nullable|string',
            'consultation_fee'     => 'required|numeric',
        ]);

        $consultation = Consultation::create($validated);

        // Mark appointment as completed
        Appointment::where('id', $validated['appointment_id'])
            ->update(['status' => 'Completed']);

        // Auto-generate claim
        $patient = $consultation->patient;
        Claim::create([
            'consultation_id'      => $consultation->id,
            'patient_id'           => $consultation->patient_id,
            'claim_number'         => 'CLM-' . strtoupper(Str::random(8)),
            'medical_aid_provider' => $patient->medical_aid_provider ?? 'N/A',
            'medical_aid_number'   => $patient->medical_aid_number ?? 'N/A',
            'amount_claimed'       => $consultation->consultation_fee,
            'status'               => 'Pending',
            'date_of_service'      => now()->toDateString(),
        ]);

        return response()->json($consultation->load(['patient', 'appointment']), 201);
    }

    public function show($id)
    {
        return response()->json(Consultation::with(['patient', 'appointment', 'claim'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        $validated = $request->validate([
            'blood_pressure'       => 'nullable|string',
            'temperature'          => 'nullable|numeric',
            'weight'               => 'nullable|numeric',
            'pulse'                => 'nullable|integer',
            'diagnosis'            => 'sometimes|string',
            'prescribed_treatment' => 'sometimes|string',
            'notes'                => 'nullable|string',
            'consultation_fee'     => 'sometimes|numeric',
        ]);

        $consultation->update($validated);
        return response()->json($consultation->load(['patient', 'appointment']));
    }
}