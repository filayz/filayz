<?php

namespace Database\Seeders;

use App\Enums\LoadPriority;
use App\Models\Mod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModSeeder extends Seeder
{
    public array $mods = [
        2545327648 => LoadPriority::critical, // Dabs Framework
        1559212036 => LoadPriority::critical, // CF
        2291785308 => LoadPriority::high, // DayZ-Expansion-Core
        2792984177 => LoadPriority::normal, // DayZ-Expansion-Missions
        2291785437 => LoadPriority::normal, // DayZ-Expansion-Vehicles
        2576460232 => LoadPriority::normal, // DayZ-Expansion-Name-Tags
        2828486817 => LoadPriority::normal, // DayZ-Expansion-Quests
        2572328470 => LoadPriority::normal, // DayZ-Expansion-Market
        2792982069 => LoadPriority::normal, // DayZ-Expansion-AI
        2793893086 => LoadPriority::normal, // DayZ-Expansion-Animations
        2116157322 => LoadPriority::normal, // DayZ-Expansion-Licensed
        1710977250 => LoadPriority::normal, // BaseBuildingPlus
        1602372402 => LoadPriority::normal, // DeerIsle
//        2842791521 => LoadPriority::low, // KOTH
        2345073965 => LoadPriority::low, // CJ187-LootChest
        2443122116 => LoadPriority::low, // SNAFU Weapons
        2170927235 => LoadPriority::low, // DrugsPLUS
        3010267444 => LoadPriority::low, // Paragon Storage
        1932611410 => LoadPriority::low, // CannabisPlus
        3069507753 => LoadPriority::low, // Hunter Mega Pack
        2303554682 => LoadPriority::low, // DogTags
        2819373632 => LoadPriority::low, // BodyBags
        2654418630 => LoadPriority::low, // BulletStackPlusAdditional
        2018887948 => LoadPriority::low, // CJ187-MoreMoney
        1832448183 => LoadPriority::low, // FlipTransport
        2122332595 => LoadPriority::low, // Vehicle3PP
        1827241477 => LoadPriority::low, // Breachingcharge
        1646187754 => LoadPriority::normal, // Code Lock (required for paragon)
        1828439124 => LoadPriority::low, // VPPAdminTools
        2792982897 => LoadPriority::low, // DayZ-Expansion-Chat
        2874295844 => LoadPriority::low, // Insta Flagpole build
        1707653948 => LoadPriority::low, // StaminaSettings
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->mods as $mod => $prio) {
            (new Mod([
                'id' => $mod,
                'load_priority' => $prio
            ]))->save();
        }
    }
}
