<?php

namespace App\Http\Controllers;

use App\Models\Grievance;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfficialGrievanceController extends Controller
{
    public function index()
    {
        $grievances = Grievance::with([
            'beneficiaryProfile',
            'distribution.entitlement.commodity',
            'reviewer',
        ])->latest()->get();

        return view('official.grievances', compact('grievances'));
    }

    public function update(Request $request, Grievance $grievance)
    {
        $data = $request->validate([
            'status' => [
                'required',
                Rule::in(['open', 'in_review', 'resolved']),
            ],
            'official_response' => [
                'required',
                'string',
                'min:5',
                'max:3000',
            ],
        ]);

        // A resolved grievance cannot be reopened in this MVP.
        if ($grievance->status === 'resolved') {
            return back()->withErrors([
                'status' => 'This grievance is already resolved.',
            ]);
        }

        $grievance->update([
            'status' => $data['status'],
            'official_response' => $data['official_response'],
            'reviewed_by' => $request->user()->id,
            'resolved_at' => $data['status'] === 'resolved'
                ? now()
                : null,
        ]);

        return redirect()
            ->route('official.grievances.index')
            ->with('success', 'Grievance updated successfully.');
    }
}
