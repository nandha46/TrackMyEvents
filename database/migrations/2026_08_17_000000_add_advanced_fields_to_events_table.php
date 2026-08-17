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
            $table->string('share_token', 64)->nullable()->unique()->after('background');
            $table->boolean('is_recurring')->default(false)->after('share_token');
            $table->string('recurrence_type')->default('none')->after('is_recurring'); // none, yearly, monthly, weekly, daily
            $table->json('tags')->nullable()->after('recurrence_type');
            $table->json('milestones')->nullable()->after('tags');
            $table->text('description')->nullable()->after('milestones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['share_token', 'is_recurring', 'recurrence_type', 'tags', 'milestones', 'description']);
        });
    }
};
