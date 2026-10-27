<?php

namespace App\Http\Controllers;

use App\Models\Entitlement;
use App\Models\RationDistribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\SmsNotification;

class RationDistributionController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->fairPriceShop;
        abort_unless($shop, 403);

        $entitlements = Entitlement::with([
            'beneficiaryProfile.user',
            'commodity',
        ])->orderBy('entitlement_month', 'desc')->get();

        $distributions = RationDistribution::with([
            'entitlement.beneficiaryProfile.user',
            'entitlement.commodity',
        ])->where('fair_price_shop_id', $shop->id)->latest()->get();

        return view('fps.distributions', compact(
            'entitlements',
            'distributions'
        ));
    }

    public function store(Request $request)
    {
        $shop = $request->user()->fairPriceShop;
        abort_unless($shop, 403);

        $data = $request->validate([
            'entitlement_id' => ['required', 'exists:entitlements,id'],
            'distributed_quantity' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,3',
            ],
        ]);

        DB::transaction(function () use ($data, $request, $shop) {
            $entitlement = Entitlement::with('commodity')
                ->whereKey($data['entitlement_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if (
                (float) $data['distributed_quantity'] >
                (float) $entitlement->quantity
            ) {
                throw ValidationException::withMessages([
                    'distributed_quantity' =>
                        'Quantity exceeds beneficiary entitlement.',
                ]);
            }

            if (
                RationDistribution::where(
                    'entitlement_id',
                    $entitlement->id
                )->exists()
            ) {
                throw ValidationException::withMessages([
                    'entitlement_id' =>
                        'Distribution already recorded.',
                ]);
            }

            $distribution = RationDistribution::create([
                'fair_price_shop_id' => $shop->id,
                'entitlement_id' => $entitlement->id,
                'distributed_by' => $request->user()->id,
                'distributed_quantity' => $data['distributed_quantity'],
                'distributed_at' => now(),
                'verification_status' => 'pending',
            ]);
            $profile = $entitlement->beneficiaryProfile;

            SmsNotification::create([
                'ration_distribution_id' => $distribution->id,
                'beneficiary_profile_id' => $profile->id,
                'message' => sprintf(
                    'PDS Trust: Your FPS reports distributing %s %s of %s. ' .
                    'Did you receive this quantity? Reply YES or NO.',
                    $distribution->distributed_quantity,
                    $entitlement->commodity->unit,
                    $entitlement->commodity->name
                ),
                'status' => 'pending',
            ]);

        });

        return back()->with(
            'success',
            'Distribution recorded. Awaiting beneficiary verification.'
        );
    }

    public function beneficiaryIndex(Request $request)
    {
        $profile = $request->user()->beneficiaryProfile;
        abort_unless($profile, 403);

        $distributions = RationDistribution::with([
            'entitlement.commodity',
            'fairPriceShop',
        ])->whereHas('entitlement', function ($query) use ($profile) {
            $query->where('beneficiary_profile_id', $profile->id);
        })->latest()->get();

        return view('beneficiary.verify', compact('distributions'));
    }

    public function verify(Request $request, RationDistribution $distribution)
    {
        $profile = $request->user()->beneficiaryProfile;

        abort_unless(
            $profile &&
            $distribution->entitlement->beneficiary_profile_id === $profile->id,
            403
        );

        $data = $request->validate([
            'verified_quantity' => [
                'required',
                'numeric',
                'min:0',
                'decimal:0,3',
            ],
        ]);

        DB::transaction(function () use ($distribution, $data) {
            $locked = RationDistribution::whereKey($distribution->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->verification_status !== 'pending') {
                throw ValidationException::withMessages([
                    'verified_quantity' => 'Already verified.',
                ]);
            }

            $matches = abs(
                (float) $locked->distributed_quantity -
                (float) $data['verified_quantity']
            ) < 0.0005;

            $locked->update([
                'verified_quantity' => $data['verified_quantity'],
                'verification_status' => $matches
                    ? 'confirmed'
                    : 'disputed',
                'verified_at' => now(),
            ]);
        });

        return back()->with('success', 'Verification submitted.');
    }
}
