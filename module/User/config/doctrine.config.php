<?php
return [
    'doctrine' => [
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    // pick any listeners you need
                    'Gedmo\Timestampable\TimestampableListener',
                ],
            ],
        ],
        'driver' => [
            'user_entity' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/orm']
            ],
            'orm_default' => [
                'drivers' => [
                    'Aqilix\OAuth2\Entity' => 'aqilix_oauth2_entity',
                    'User\Entity' => 'user_entity',

                ]
            ]
        ],
    ],
    'data-fixture' => [
        'fixtures' => __DIR__ . '/../src/V1/Fixture'
    ],
];
