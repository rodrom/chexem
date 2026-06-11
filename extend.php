<?php

use Flarum\Extend;
use Rodrom\Chexem\Listener\AssignGroupFromExternalDb;

return [
    (new Extend\Event())
        ->listen(
            \Flarum\User\Event\Activated::class,
            AssignGroupFromExternalDb::class
        ),
];
