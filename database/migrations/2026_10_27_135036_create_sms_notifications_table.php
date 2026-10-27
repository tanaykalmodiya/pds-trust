
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ration_distribution_id')
                ->unique()
                ->constrained('ration_distributions')
                ->restrictOnDelete();

            $table->foreignId('beneficiary_profile_id')
                ->constrained('beneficiary_profiles')
                ->restrictOnDelete();

            $table->text('message');

            $table->string('status', 30)->default('pending');

            $table->string('response', 30)->nullable();

            $table->timestamp('responded_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_notifications');
    }
};
