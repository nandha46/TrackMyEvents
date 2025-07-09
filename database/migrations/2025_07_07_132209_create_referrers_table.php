<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('referrers', function (Blueprint $table) {
            $table->id();
            $table->text('referrer_url', 2048);
            $table->text('referrer_domain', 255);
            $table->string('referrer_type', 50);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE referrers ADD UNIQUE INDEX referrer_url_partial_unique (referrer_url(191))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrers');
    }
};
