<?php

namespace Rodrom\Chexem\Listener;

use Flarum\Group\Group;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Log\LoggerInterface;

class AssignGroupFromExternalDb
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

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
            'database' => $external['database'],
            'username' => $external['username'],
            'password' => $external['password'],
        ]);

        $exists = false;

        try {
            $exists = $capsule
            ->getConnection()
            ->table($external['table'])
            ->whereRaw("LOWER({$external['column']}) = ?", [
                strtolower($user->email)
            ])
            ->exists();

        } catch (\Throwable $e) {
            $this->logger->warning(
                'External database lookup failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]
            );

            return;
        }

        if (!$exists) {
            $this->logger->info(
                'Email not found in external database',
                [
                    'user_id' => $user->id,
                ]
            );

            return;
        }

        $group = Group::where('name_singular', $external['flarum_group'])->first();

        if (!$group) {
            $this->logger->info(
                'Flarum group singular name not found',
                [
                    'flarum_group' => $external['flarum_group'],
                ]
            );

            return;
        }

        $user->groups()->syncWithoutDetaching([
            $group->id
        ]);

        $this->logger->info(
            'Group assigned from external database match',
            [
                'user_id' => $user->id,
                'group_id' => $group->id,
                'exists' => $exists,
            ]
        );
    }
}