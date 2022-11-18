<?php
return [
    'handle' => [
        'context' => [
            'providers' => [
                \Swooen\Provider\LogProvider::class,
            ]
        ]
    ],
    'configs' => [
        ['logging', 'logging.php']
    ]
];
