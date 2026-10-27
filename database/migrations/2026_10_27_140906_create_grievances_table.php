
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grievances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('beneficiary_profile_id')
                ->constrained('beneficiary_profiles')
                ->restrictOnDelete();

            $table->foreignId('ration_distribution_id')
                ->nullable()
                ->constrained('ration_distributions')
                ->restrictOnDelete();

            $table->string('subject', 150);
            $table->text('description');

            $table->string('status', 30)->default('open');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('official_response')->nullable();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            $table->index(['beneficiary_profile_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grievances');
    }
};
