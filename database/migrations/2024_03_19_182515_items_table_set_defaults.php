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
            $table->integer('nominal')->nullable()->change();
            $table->integer('lifetime')->nullable()->change();
            $table->integer('restock')->nullable()->change();
            $table->unsignedInteger('min')->nullable()->change();
            $table->integer('quantmin')->nullable()->change();
            $table->integer('quantmax')->nullable()->change();
            $table->unsignedInteger('cost')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
};
