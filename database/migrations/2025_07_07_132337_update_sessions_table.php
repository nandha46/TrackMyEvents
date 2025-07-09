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
        // Here we are not creating just adding new columns, so Schema::table is used
        Schema::table('sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('initial_page_id');
            $table->unsignedBigInteger('country_id');
            $table->string('city');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('initial_page_id')->references('id')->on('pages');
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Removing columns from framework provided sessions table
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn(['initial_page_id', 'country_id', 'city']);
        });
    }
};
