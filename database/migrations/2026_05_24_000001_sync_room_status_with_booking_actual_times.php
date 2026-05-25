<?php

use Illuminate\Database\Migrations\Migration;
return new class extends Migration
{
    public function up(): void
    {
        // Deferred: the previous trigger used MySQL syntax. Room status is
        // currently synchronized by the Booking model to keep Oracle migration safe.
    }

    public function down(): void
    {
        // No database trigger is created by this migration.
    }
};
