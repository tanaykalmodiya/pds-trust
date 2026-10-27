
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fair_price_shops', function (Blueprint $table) {
            $table->id();

            $table->string('shop_code', 50)->unique();
            $table->string('name', 255);

            $table->foreignId('owner_id')
                ->unique()
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('address');
            $table->string('district', 100);
            $table->string('state', 100);
            $table->string('pincode', 6);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fair_price_shops');
    }
};
