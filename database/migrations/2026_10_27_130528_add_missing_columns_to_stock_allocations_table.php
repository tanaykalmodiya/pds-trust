
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // All required columns and indexes are already created
        // by the original stock_allocations migration.
        //
        // This migration is retained for migration history
        // compatibility.
    }

    public function down(): void
    {
        // Nothing to reverse.
    }
};
