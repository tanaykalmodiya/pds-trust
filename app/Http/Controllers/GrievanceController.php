<?php

namespace App\Http\Controllers;

use App\Models\Grievance;
use App\Models\RationDistribution;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GrievanceController extends Controller
{
    public function index(Request $request)
    {
        $profile = $request->user()->beneficiaryProfile;

        abort_unless($profile, 403);

        $grievances = Grievance::where(
            'beneficiary_profile_id',
            $profile->id
        )->latest()->get();

        $distributions = RationDistribution::with([
            'entitlement.commodity',
        ])
            ->whereHas('entitlement', function ($query) use ($profile) {
                $query->where('beneficiary_profile_id', $profile->id);
            })
            ->latest()
            ->get();

        return view('beneficiary.grievances', compact(
            'grievances',
            'distributions'
        ));
    }

    public function store(Request $request)
    {
        $profile = $request->user()->beneficiaryProfile;

        abort_unless($profile, 403);

        $data = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'min:10', 'max:3000'],
            'ration_distribution_id' => [
                'nullable',
                'integer',
                Rule::exists('ration_distributions', 'id')
                    ->where(function ($query) use ($profile) {
                        $query->whereIn(
                            'entitlement_id',
                            \App\Models\Entitlement::where(
                                'beneficiary_profile_id',
                                $profile->id
                            )->select('id')
                        );
                    }),
            ],
        ]);

        Grievance::create([
            'beneficiary_profile_id' => $profile->id,
            'ration_distribution_id' =>
                $data['ration_distribution_id'] ?? null,
            'subject' => $data['subject'],
            'description' => $data['description'],
            'status' => 'open',
        ]);

        return redirect()
            ->route('beneficiary.grievances.index')
            ->with('success', 'Your grievance has been submitted.');
    }
}
