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
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 255);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('referrer_id');
            $table->unsignedBigInteger('ip_address_id');
            $table->unsignedBigInteger('browser_id');
            $table->unsignedBigInteger('os_id');
            $table->string('device_type');
            $table->foreign('session_id')->references('id')->on('sessions')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('page_id')->references('id')->on('pages')->cascadeOnDelete();
            $table->foreign('referrer_id')->references('id')->on('referrers')->cascadeOnDelete();
            $table->foreign('ip_address_id')->references('id')->on('ip_addresses')->cascadeOnDelete();
            $table->foreign('browser_id')->references('id')->on('browsers')->cascadeOnDelete();
            $table->foreign('os_id')->references('id')->on('operating_systems')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
