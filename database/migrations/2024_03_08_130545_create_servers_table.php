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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->unsignedInteger('port_sftp')->default(2224)->unique();
            $table->unsignedInteger('port_game')->default(2302)->unique();
            $table->unsignedInteger('port_reserved')->default(2303)->unique();
            $table->unsignedInteger('port_battl_eye')->default(2304)->unique();
            $table->unsignedInteger('port_steam_query')->default(27016)->unique();
            $table->unsignedInteger('port_rcon')->default(2305)->unique();

            $table->unsignedInteger('fps_limit')->default(300);
            $table->unsignedInteger('cpu_count');

            $table->unsignedInteger('pid')->nullable();

            $table->timestamps();

            $table->timestamp('requested_boot_at')->nullable();
            $table->timestamp('booted_at')->nullable();
            $table->timestamp('requested_exit_at')->nullable();
            $table->timestamp('exited_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
