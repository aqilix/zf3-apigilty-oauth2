<?php
return [
    'doctrine' => [
        'driver' => [
            'aqilix_oauth2_entity' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/OAuth2/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    'Aqilix\OAuth2\Entity' => 'aqilix_oauth2_entity'
                ]
            ]
        ],
    ]
];
