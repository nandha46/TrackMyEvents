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
        Schema::create('operating_systems', function (Blueprint $table) {
            $table->id();
            $table->string('os_name', 100)->default('unknown');
            $table->string('os_version', 50)->nullable();
            $table->unique(['os_name', 'os_version']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operating_systems');
    }
};
