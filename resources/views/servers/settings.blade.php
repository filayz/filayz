@php /** @var App\Models\Server $server */ @endphp
hostname = "{{ $server->name }}";  // Server name
password = "";              // Password to connect to the server
passwordAdmin = "{{ $server->password_admin }}";         // Password to become a server admin

enableWhitelist = 0;        // Enable/disable whitelist (value 0-1)

maxPlayers = {{ $server->player_slots }};            // Maximum amount of players

verifySignatures = 2;       // Verifies .pbos against .bisign files. (only 2 is supported)
forceSameBuild = 1;         // When enabled, the server will allow the connection only to clients with same the .exe revision as the server (value 0-1)

disableVoN = {{ $server->voice_enabled ? '0' : '1' }};             // Enable/disable voice over network (value 0-1)
vonCodecQuality = 20;        // Voice over network codec quality, the higher the better (values 0-30)

disable3rdPerson= {{ $server->third_person_enabled ? '0' : '1' }};         // Toggles the 3rd person view for players (value 0-1)
disableCrosshair= {{ $server->crosshair_enabled ? '0' : '1' }};         // Toggles the cross-hair (value 0-1)

disablePersonalLight = {{ $server->personal_light_enabled ? '0' : '1' }};   // Disables personal light for all clients connected to server
lightingConfig = 0;         // 0 for brighter night setup, 1 for darker night setup

serverTime="SystemTime";    // Initial in-game time of the server. "SystemTime" means the local time of the machine. Another possibility is to set the time to some value in "YYYY/MM/DD/HH/MM" format, f.e. "2015/4/8/17/23" .
serverTimeAcceleration={{ $server->time_day_speed }};  // Accelerated Time (value 0-24)// This is a time multiplier for in-game time. In this case, the time would move 24 times faster than normal, so an entire day would pass in one hour.
serverNightTimeAcceleration={{ $server->time_night_speed }};  // Accelerated Nigh Time - The numerical value being a multiplier (0.1-64) and also multiplied by serverTimeAcceleration value. Thus, in case it is set to 4 and serverTimeAcceleration is set to 2, night time would move 8 times faster than normal. An entire night would pass in 3 hours.
serverTimePersistent=1;     // Persistent Time (value 0-1)// The actual server time is saved to storage, so when active, the next server start will use the saved time value.

guaranteedUpdates=1;        // Communication protocol used with game server (use only number 1)

loginQueueConcurrentPlayers=5;  // The number of players concurrently processed during the login process. Should prevent massive performance drop during connection when a lot of people are connecting at the same time.
loginQueueMaxPlayers=500;       // The maximum number of players that can wait in login queue

instanceId = {{ $server->id }};             // DayZ server instance id, to identify the number of instances per box and their storage folders with persistence files

storageAutoFix = 1;         // Checks if the persistence files are corrupted and replaces corrupted ones with empty ones (value 0-1)

steamQueryPort = {{ $server->port_rcon }};			// defines Steam query port, should fix the issue with server not being visible in client server browser

vppDisablePassword = 0; // Disables the requirement of having to fill in a password when using VPP Admin
