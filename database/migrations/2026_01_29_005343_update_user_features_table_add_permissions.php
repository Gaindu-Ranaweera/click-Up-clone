<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_features', function (Blueprint $table) {
            if (!Schema::hasColumn('user_features', 'can_edit')) {
                $table->boolean('can_edit')->default(true);
            }
            if (!Schema::hasColumn('user_features', 'can_delete')) {
                $table->boolean('can_delete')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_features', function (Blueprint $table) {
            $table->dropColumn(['can_edit', 'can_delete']);
        });
    }
};
