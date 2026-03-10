<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index()
    {
        return response()->json(Claim::with(['patient', 'consultation'])->get());
    }

    public function show($id)
    {
        return response()->json(Claim::with(['patient', 'consultation'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $claim = Claim::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:Pending,Submitted,Approved,Rejected',
        ]);

        $claim->update($validated);
        return response()->json($claim);
    }

    public function exportPdf($id)
    {
        $claim = Claim::with(['patient', 'consultation'])->findOrFail($id);

        $pdf = Pdf::loadView('claims.pdf', [
            'claim' => $claim,
        ])->setPaper('a4');

        return $pdf->download('claim-' . $claim->claim_number . '.pdf');
    }
}