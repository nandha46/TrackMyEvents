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
        Schema::table('events', function (Blueprint $table) {
            $table->string('pexels_id')->nullable()->after('background');
            $table->string('pexels_photographer')->nullable()->after('pexels_id');
            $table->string('pexels_photographer_url')->nullable()->after('pexels_photographer');
            $table->string('pexels_photo_url')->nullable()->after('pexels_photographer_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'pexels_id',
                'pexels_photographer',
                'pexels_photographer_url',
                'pexels_photo_url',
            ]);
        });
    }
};
