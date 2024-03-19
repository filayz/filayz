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
        Schema::table('servers', function (Blueprint $table) {
            $table->ipAddress()->after('name');
            $table->string('password_rcon')->after('port_rcon');
            $table->string('password_admin')->after('port_rcon');

            $table->unsignedBigInteger('player_slots')->default(10)->after('pid');
            $table->unsignedBigInteger('player_count')->default(0)->after('pid');

            $table->boolean('voice_enabled')->default(true)->after('fps_limit');
            $table->boolean('third_person_enabled')->default(true)->after('fps_limit');
            $table->boolean('crosshair_enabled')->default(true)->after('fps_limit');
            $table->boolean('personal_light_enabled')->default(true)->after('fps_limit');

            $table->unsignedBigInteger('time_day_speed')->default(1)->after('fps_limit');
            $table->unsignedBigInteger('time_night_speed')->default(1)->after('fps_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn([
                'ip_address', 'password_rcon', 'password_admin',
                'player_slots', 'player_count',
                'voice_enabled', 'third_person_enabled', 'crosshair_enabled', 'personal_light_enabled'
            ]);
        });
    }
};
