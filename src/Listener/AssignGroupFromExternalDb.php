<?php

namespace Rodrom\Chexem\Listener;

use Illuminate\Support\Facades\DB;
use Flarum\Group\Group;
use Illuminate\Database\Capsule\Manager as Capsule;

class AssignGroupFromExternalDb
{
    public function handle($event)
    {
        $user = $event->user;

        $config = resolve('flarum.config');

        $external = $config['external_database'];

        $capsule = new Capsule();

        $capsule->addConnection([
            'driver' => $external['driver'],
            'host' => $external['host'],
            'port' => $external['port'],
            'database' => $external['username'],
            'password' => $external['password'],
        ]);

        $exists = $capsule
            ->getConnection()
            ->table($external['table'])
            ->whereRaw('LOWER(email) = ?', [
                strtolower($user->email)
            ])
            ->exists();

        if (!$exists) {
            return;
        }

        $group = Group::where('name_singular', 'Participante')->first();

        if (!$group) {
            return;
        }

        $user->groups()->syncWithoutDetaching([
            $group->id
        ]);
    }
}