<?php

namespace App\Http\Controllers;

use App\Models\Claim;
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
}