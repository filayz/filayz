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
        Schema::create('missions', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('path');

            $table->string('path_economy_xml')->default('db/economy.xml');
            $table->string('path_events_xml')->default('db/events.xml');
            $table->string('path_globals_xml')->default('db/globals.xml');
            $table->string('path_types_xml')->default('db/types.xml');
            $table->string('path_messages_xml')->default('db/messages.xml');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};
