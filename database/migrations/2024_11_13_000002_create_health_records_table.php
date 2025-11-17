<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->unsignedInteger('sistolik');
            $table->unsignedInteger('diastolik');
            $table->unsignedInteger('denyut_nadi');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Indexes untuk performa query
            $table->index('patient_id');
            $table->index('created_at');
            $table->index(['patient_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
