
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_receipts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stock_allocation_id')
                ->constrained('stock_allocations')
                ->restrictOnDelete();

            $table->foreignId('received_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->decimal('received_quantity', 12, 3);

            $table->timestamp('received_at');

            $table->string('remarks', 500)->nullable();

            $table->timestamps();

            // One receipt acknowledgement per allocation
            // in our initial prototype.
            $table->unique('stock_allocation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_receipts');
    }
};
