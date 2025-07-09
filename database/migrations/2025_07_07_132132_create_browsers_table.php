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
        Schema::create('browsers', function (Blueprint $table) {
            $table->id();
            $table->string('browser_name', 100);
            $table->string('browser_version', 50);
            $table->string('browser_major_version', 20);
            $table->unique(['browser_name', 'browser_version']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('browsers');
    }
};
