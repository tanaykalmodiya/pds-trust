
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('stock_allocations', 'fair_price_shop_id')) {
            Schema::table('stock_allocations', function (Blueprint $table) {
                $table->foreignId('fair_price_shop_id')
                    ->constrained('fair_price_shops')
                    ->restrictOnDelete();
            });
        }

        if (!Schema::hasColumn('stock_allocations', 'commodity_id')) {
            Schema::table('stock_allocations', function (Blueprint $table) {
                $table->foreignId('commodity_id')
                    ->constrained('commodities')
                    ->restrictOnDelete();
            });
        }

        if (!Schema::hasColumn('stock_allocations', 'allocated_by')) {
            Schema::table('stock_allocations', function (Blueprint $table) {
                $table->foreignId('allocated_by')
                    ->constrained('users')
                    ->restrictOnDelete();
            });
        }

        if (!Schema::hasColumn('stock_allocations', 'allocation_month')) {
            Schema::table('stock_allocations', function (Blueprint $table) {
                $table->date('allocation_month');
            });
        }

        if (!Schema::hasColumn('stock_allocations', 'allocated_quantity')) {
            Schema::table('stock_allocations', function (Blueprint $table) {
                $table->decimal('allocated_quantity', 12, 3);
            });
        }

        if (!Schema::hasColumn('stock_allocations', 'status')) {
            Schema::table('stock_allocations', function (Blueprint $table) {
                $table->string('status', 30)->default('allocated');
            });
        }

        if (!Schema::hasColumn('stock_allocations', 'allocated_at')) {
            Schema::table('stock_allocations', function (Blueprint $table) {
                $table->timestamp('allocated_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        // This repair migration does not remove columns,
        // because some may belong to the original table.
    }
};
