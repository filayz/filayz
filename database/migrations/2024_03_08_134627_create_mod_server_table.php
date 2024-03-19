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
        Schema::create('mod_server', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Server::class);
            $table->foreignIdFor(\App\Models\Mod::class);

            $table->boolean('enabled')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mod_server');
    }
};
