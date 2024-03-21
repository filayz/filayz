# Filayz

The DayZ gameserver control panel based on Laravel, Filament and steamcmd for Linux servers.

### Requirements

- The Laravel requirements: https://laravel.com/docs/10.x/deployment#server-requirements
- A linux server, Ubuntu will work.

This server does not take care of installing or configuring requirements like a webserver or database. If you need
something simple check out https://forge.laravel.com to provision your server or use any other webhosting control
panel.

### Features

Filayz helps manage your DayZ dedicated game servers.

- Install and update DayZ game servers.
- Install and update DayZ steam workshop mods.
- Run the DayZ game servers with its own daemon (`php artisan app:server:service`), see "Daemon" below.
- Reads mission and mod types (items) and allows editing them per server.
- Allows defining locations (Positions) for use with loot boxes, KOTH and air drops.

### To Do

- Allow signups.
- Finish loot drops, KOTH and air drop configuration and writing to server with Loot and Position configurations.
- Allow loot sets for use with different mods.
- Test running multiple servers.

### Installation

- Clone the repository `git clone git@github.com:filayz/filayz <website root>`
- Make sure the website vhost file points to `<website root>/public`
- Run `php artisan key:generate`
- Create a database and set the values in `.env` see `.env.example` and the Laravel docs for more information.
- Run `php artisan migrate --seed`
- Create a user using `php artisan make:filament-user`, go into your database and toggle `admin` to `1` for this user.

### Configuration


#### Daemon

You will need to make sure the daemon is running. You can either set up a linux service or use supervisor to
set it up. Here's an example:

```
[program:filayz]
directory=/home/forge/filayz/
command=php artisan app:server:service
environment=HOME="/home/forge",USER="forge"


process_name=%(program_name)s_%(process_num)02d
autostart=true
autorestart=true
user=forge
numprocs=1
startsecs=1
redirect_stderr=true
stdout_logfile=/home/forge/.forge/filayz.log
stdout_logfile_maxbytes=5MB
stdout_logfile_backups=3
stopwaitsecs=30
stopsignal=SIGTERM
stopasgroup=true
killasgroup=true
stopsignal=QUIT
```
