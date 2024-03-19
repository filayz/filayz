<?php

use App\Models\Mod;
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
        Schema::create('mod_files', function (Blueprint $table) {
            $table->id();

            $table->string('path');
            $table->string('type');

            $table->boolean('editable')->default(false);

            $table->longText('contents')->nullable();

            $table->foreignId('mod_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mod_files');
    }
};
