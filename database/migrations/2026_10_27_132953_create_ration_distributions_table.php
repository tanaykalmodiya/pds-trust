
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ration_distributions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fair_price_shop_id')
                ->constrained('fair_price_shops')
                ->restrictOnDelete();

            $table->foreignId('entitlement_id')
                ->unique()
                ->constrained('entitlements')
                ->restrictOnDelete();

            $table->foreignId('distributed_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->decimal('distributed_quantity', 12, 3);
            $table->timestamp('distributed_at')->useCurrent();

            $table->string('verification_status', 30)
                ->default('pending');

            $table->decimal('verified_quantity', 12, 3)
                ->nullable();

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ration_distributions');
    }
};
