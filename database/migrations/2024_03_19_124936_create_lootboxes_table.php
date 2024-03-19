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
        Schema::create('lootboxes', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->boolean('allow_forcing_access')->default(false);
            $table->boolean('shines_light')->default(false);
            $table->float('loot_spawn_chance');

            $table->foreignIdFor(\App\Models\Server::class);
            $table->foreignIdFor(\App\Models\Key::class);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lootboxes');
    }
};
