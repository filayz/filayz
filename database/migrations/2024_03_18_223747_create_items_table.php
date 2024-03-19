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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->longText('description')->nullable();
            $table->foreignIdFor(\App\Models\Mod::class);
            $table->foreignIdFor(\App\Models\Key::class);
            $table->foreignIdFor(\App\Models\Server::class)->nullable();

            $table->integer('nominal');
            $table->integer('lifetime');
            $table->integer('restock');
            $table->unsignedInteger('min');
            $table->integer('quantmin');
            $table->integer('quantmax');
            $table->unsignedInteger('cost');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
