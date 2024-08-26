<?php

return [

    'disks' => [
        'tmp-for-tests' => [
            'driver' => 'local',
            'root' => storage_path('app/livewire-tmp'),
        ],
    ],

];
