<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        return response()->json(Patient::whereNull('archived_at')->get());
    }

    public function archived()
    {
        return response()->json(
            Patient::whereNotNull('archived_at')
                ->orderByDesc('archived_at')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'            => 'required|string',
            'date_of_birth'        => 'required|date',
            'gender'               => 'required|in:Male,Female,Other',
            'national_id'          => 'required|string|unique:patients',
            'phone_number'         => 'required|string',
            'address'              => 'required|string',
            'medical_aid_provider' => 'nullable|string',
            'medical_aid_number'   => 'nullable|string',
            'allergies'            => 'nullable|string',
            'chronic_conditions'   => 'nullable|string',
        ]);

        $patient = Patient::create($validated);
        return response()->json($patient, 201);
    }

    public function show($id)
    {
        $patient = Patient::with(['appointments', 'consultations', 'claims'])
            ->whereNull('archived_at')
            ->findOrFail($id);
        return response()->json($patient);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::whereNull('archived_at')->findOrFail($id);

        $validated = $request->validate([
            'full_name'            => 'sometimes|string',
            'date_of_birth'        => 'sometimes|date',
            'gender'               => 'sometimes|in:Male,Female,Other',
            'national_id'          => 'sometimes|string|unique:patients,national_id,' . $id,
            'phone_number'         => 'sometimes|string',
            'address'              => 'sometimes|string',
            'medical_aid_provider' => 'nullable|string',
            'medical_aid_number'   => 'nullable|string',
            'allergies'            => 'nullable|string',
            'chronic_conditions'   => 'nullable|string',
        ]);

        $patient->update($validated);
        return response()->json($patient);
    }

    public function destroy($id)
    {
        $patient = Patient::whereNull('archived_at')->findOrFail($id);
        $patient->delete();
        return response()->json(['message' => 'Patient deleted successfully']);
    }

    public function archive($id)
    {
        $patient = Patient::whereNull('archived_at')->findOrFail($id);
        $patient->update(['archived_at' => now()]);

        return response()->json(['message' => 'Patient archived successfully']);
    }

    public function restore($id)
    {
        $patient = Patient::whereNotNull('archived_at')->findOrFail($id);
        $patient->update(['archived_at' => null]);

        return response()->json(['message' => 'Patient restored successfully']);
    }
}