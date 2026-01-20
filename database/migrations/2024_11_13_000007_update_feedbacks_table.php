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
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating')->nullable()->after('message'); // rating 1-5
            $table->boolean('anonymous')->default(false)->after('rating');
            $table->boolean('follow_up_sent')->default(false)->after('anonymous');
            $table->timestamp('follow_up_sent_at')->nullable()->after('follow_up_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn(['rating', 'anonymous', 'follow_up_sent', 'follow_up_sent_at']);
        });
    }
};
