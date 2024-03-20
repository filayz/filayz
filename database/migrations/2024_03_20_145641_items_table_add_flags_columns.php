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
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('count_in_cargo')->default(false);
            $table->boolean('count_in_hoarder')->default(false);
            $table->boolean('count_in_map')->default(false);
            $table->boolean('count_in_player')->default(false);
            $table->boolean('crafted')->default(false);
            $table->boolean('deloot')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('count_in_cargo');
            $table->dropColumn('count_in_hoarder');
            $table->dropColumn('count_in_map');
            $table->dropColumn('count_in_player');
            $table->dropColumn('crafted');
            $table->dropColumn('deloot');
        });
    }
};
