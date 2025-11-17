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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['pasien', 'dokter'])->default('pasien')->after('password');
            $table->string('phone', 20)->nullable()->after('role');
            $table->date('birthdate')->nullable()->after('phone');
            $table->text('address')->nullable()->after('birthdate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'birthdate', 'address']);
        });
    }
};
