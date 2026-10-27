<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entitlements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('beneficiary_profile_id')
                ->constrained('beneficiary_profiles')
                ->restrictOnDelete();

            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->restrictOnDelete();

            // First day of the entitlement month.
            $table->date('entitlement_month');

            $table->decimal('quantity', 10, 3);

            $table->timestamps();

            $table->unique(
                [
                    'beneficiary_profile_id',
                    'commodity_id',
                    'entitlement_month',
                ],
                'entitlements_beneficiary_commodity_month_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entitlements');
    }
};
