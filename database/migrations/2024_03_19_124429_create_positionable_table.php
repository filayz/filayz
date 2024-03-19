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
        Schema::create('positionable', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Position::class);
            $table->string('positionable_type');
            $table->unsignedBigInteger('positionable_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positionable');
    }
};
