
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_allocations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fair_price_shop_id')
                ->constrained('fair_price_shops')
                ->restrictOnDelete();

            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->restrictOnDelete();

            $table->foreignId('allocated_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->date('allocation_month');

            $table->decimal('allocated_quantity', 12, 3);

            $table->string('status', 30)->default('allocated');

            $table->timestamp('allocated_at')->useCurrent();

            $table->timestamps();

            $table->index([
                'fair_price_shop_id',
                'allocation_month',
            ], 'stock_allocations_shop_month_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_allocations');
    }
};
