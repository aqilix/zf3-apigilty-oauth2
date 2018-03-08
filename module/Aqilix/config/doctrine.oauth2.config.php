<?php
return [
    'doctrine' => [
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    // pick any listeners you need
                    'Gedmo\Timestampable\TimestampableListener',
                    'Gedmo\SoftDeleteable\SoftDeleteableListener'
                ],
            ],
        ],
        'driver' => [
            'aqilix_oauth2_entity' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/orm/oauth2']
            ],
            'orm_default' => [
                'drivers' => [
                    'Aqilix\OAuth2\Entity' => 'aqilix_oauth2_entity'
                ]
            ]
        ],
        'configuration' => [
            'orm_default' => [
                'filters' => [
                    'soft-deleteable' => 'Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter'
                ]
            ]
        ]
    ]
];
