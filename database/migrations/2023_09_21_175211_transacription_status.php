<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ips', function (Blueprint $table) {
            $table->enum('transcription_status', ['TRANSCRIBING', 'TRANSCRIBED', 'FAILED'])->default('TRANSCRIBING');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ips', function (Blueprint $table) {
            //
        });
    }
};
