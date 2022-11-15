<?php
return [
    'handlers' => [
        [
            'handler' => 'syslog',
            'host' => env('SYSLOG_HOST', '127.0.0.1'),
            'port' => env('SYSLOG_PORT', 514)
        ],
        [
            'handler' => 'file',
            'dir' => 'logs/'
        ]
    ]

];
