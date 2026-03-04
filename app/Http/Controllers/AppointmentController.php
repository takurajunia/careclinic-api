<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with('patient');

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        } elseif ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        return response()->json($query->orderBy('date')->orderBy('time')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date'       => 'required|date',
            'time'       => 'required',
            'reason'     => 'required|string',
            'status'     => 'in:Scheduled,Completed,Cancelled,No Show',
        ]);

        // Check for double booking
        $conflict = Appointment::where('date', $validated['date'])
            ->where('time', $validated['time'])
            ->where('status', 'Scheduled')
            ->exists();

        if ($conflict) {
            return response()->json([
                'message' => 'This time slot is already booked. Please choose a different time.'
            ], 409);
        }

        $appointment = Appointment::create($validated);
        return response()->json($appointment->load('patient'), 201);
    }

    public function show($id)
    {
        return response()->json(Appointment::with('patient')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id',
            'date'       => 'sometimes|date',
            'time'       => 'sometimes',
            'reason'     => 'sometimes|string',
            'status'     => 'sometimes|in:Scheduled,Completed,Cancelled,No Show',
        ]);

        $appointment->update($validated);
        return response()->json($appointment->load('patient'));
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted successfully']);
    }

    public function today()
    {
        $appointments = Appointment::with('patient')
            ->whereDate('date', today())
            ->orderBy('time')
            ->get();

        return response()->json($appointments);
    }
}