<?php

namespace App\Http\Controllers;

use App\Models\SmsNotification;
use App\Models\RationDistribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SmsSimulatorController extends Controller
{
    public function index(Request $request)
    {
        $profile = $request->user()->beneficiaryProfile;

        abort_unless($profile, 403);

        $messages = SmsNotification::with([
            'distribution.entitlement.commodity',
        ])
            ->where('beneficiary_profile_id', $profile->id)
            ->latest()
            ->get();

        return view('beneficiary.sms', compact('messages'));
    }

    public function reply(Request $request, SmsNotification $sms)
    {
        $profile = $request->user()->beneficiaryProfile;

        abort_unless(
            $profile && $sms->beneficiary_profile_id === $profile->id,
            403
        );

        $data = $request->validate([
            'response' => ['required', 'in:YES,NO'],
            'received_quantity' => [
                'required_if:response,NO',
                'nullable',
                'numeric',
                'min:0',
                'decimal:0,3',
            ],
        ]);

        DB::transaction(function () use ($sms, $data) {
            $distribution = RationDistribution::whereKey(
                $sms->ration_distribution_id
            )->lockForUpdate()->firstOrFail();

            $notification = SmsNotification::whereKey($sms->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($distribution->verification_status !== 'pending') {
                throw ValidationException::withMessages([
                    'response' => 'This distribution has already been verified.',
                ]);
            }

            if ($notification->status !== 'pending') {
                throw ValidationException::withMessages([
                    'response' => 'This SMS has already been answered.',
                ]);
            }

            $received = $data['response'] === 'YES'
                ? $distribution->distributed_quantity
                : $data['received_quantity'];

            $matches = abs(
                (float) $distribution->distributed_quantity -
                (float) $received
            ) < 0.0005;

            $distribution->update([
                'verified_quantity' => $received,
                'verification_status' => $matches
                    ? 'confirmed'
                    : 'disputed',
                'verified_at' => now(),
            ]);

            $notification->update([
                'response' => $data['response'],
                'status' => 'responded',
                'responded_at' => now(),
            ]);
        });

        return redirect()
            ->route('beneficiary.sms.index')
            ->with('success', 'Simulated SMS reply processed successfully.');
    }
}
