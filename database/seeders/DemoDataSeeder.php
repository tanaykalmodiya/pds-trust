<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new \RuntimeException(
                'Demo data must not be seeded in production.'
            );
        }

        DB::transaction(function () {
            $owner = User::where('email', 'fps@pdstrust.test')->firstOrFail();
            $official = User::where('email', 'official@pdstrust.test')->firstOrFail();

            $beneficiary = User::updateOrCreate(
                ['email' => 'beneficiary@pdstrust.test'],
                [
                    'name' => 'Demo Beneficiary',
                    'password' => Hash::make('DemoPass123!'),
                    'role' => 'beneficiary',
                ]
            );

            $profileId = DB::table('beneficiary_profiles')->updateOrInsert(
                ['user_id' => $beneficiary->id],
                [
                    'ration_card_number' => 'DEMO-RC-001',
                    'phone_number' => null,
                    'preferred_language' => 'en',
                    'sms_consent' => false,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $profile = DB::table('beneficiary_profiles')
                ->where('user_id', $beneficiary->id)
                ->first();

            $shop = DB::table('fair_price_shops')
    ->where('owner_id', $owner->id)
    ->first();

if (!$shop) {
    $shopId = DB::table('fair_price_shops')->insertGetId([
        'shop_code' => 'DEMO-FPS-001',
        'name' => 'Demo Fair Price Shop',
        'owner_id' => $owner->id,
        'address' => 'Fictional demonstration address',
        'district' => 'Demo District',
        'state' => 'Madhya Pradesh',
        'pincode' => '000000',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $shop = DB::table('fair_price_shops')
        ->where('id', $shopId)
        ->first();
}

            DB::table('commodities')->updateOrInsert(
                ['code' => 'DEMO-RICE'],
                [
                    'name' => 'Demo Rice',
                    'unit' => 'kg',
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $commodity = DB::table('commodities')
                ->where('code', 'DEMO-RICE')
                ->first();

            $month = '2026-10-01';

            DB::table('entitlements')->updateOrInsert(
                [
                    'beneficiary_profile_id' => $profile->id,
                    'commodity_id' => $commodity->id,
                    'entitlement_month' => $month,
                ],
                [
                    'quantity' => 5,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $entitlement = DB::table('entitlements')
                ->where('beneficiary_profile_id', $profile->id)
                ->where('commodity_id', $commodity->id)
                ->where('entitlement_month', $month)
                ->first();

            $allocation = DB::table('stock_allocations')
                ->where('fair_price_shop_id', $shop->id)
                ->where('commodity_id', $commodity->id)
                ->where('allocation_month', $month)
                ->where('allocated_by', $official->id)
                ->first();

            if (!$allocation) {
                $allocationId = DB::table('stock_allocations')->insertGetId([
                    'fair_price_shop_id' => $shop->id,
                    'commodity_id' => $commodity->id,
                    'allocated_by' => $official->id,
                    'allocation_month' => $month,
                    'allocated_quantity' => 100,
                    'status' => 'allocated',
                    'allocated_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $allocationId = $allocation->id;
            }

            DB::table('stock_receipts')->updateOrInsert(
                ['stock_allocation_id' => $allocationId],
                [
                    'received_by' => $owner->id,
                    'received_quantity' => 95,
                    'received_at' => now(),
                    'remarks' => 'Demo: received 5 kg less than allocated.',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            DB::table('ration_distributions')->updateOrInsert(
                ['entitlement_id' => $entitlement->id],
                [
                    'fair_price_shop_id' => $shop->id,
                    'distributed_by' => $owner->id,
                    'distributed_quantity' => 5,
                    'distributed_at' => now(),
                    'verification_status' => 'disputed',
                    'verified_quantity' => 3,
                    'verified_at' => now(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $distribution = DB::table('ration_distributions')
                ->where('entitlement_id', $entitlement->id)
                ->first();

            DB::table('grievances')->updateOrInsert(
                [
                    'beneficiary_profile_id' => $profile->id,
                    'ration_distribution_id' => $distribution->id,
                    'subject' => 'Demo ration quantity discrepancy',
                ],
                [
                    'description' => 'The FPS recorded 5 kg, but I received only 3 kg.',
                    'status' => 'open',
                    'reviewed_by' => null,
                    'official_response' => null,
                    'resolved_at' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        });
    }
}
