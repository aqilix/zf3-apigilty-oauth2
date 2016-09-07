<?php
return [
    'service_manager' => [
        'factories' => [
            'signup' => 'User\V1\Service\SignupFactory'
        ],
        'abstract_factories' => [
            'Aqilix\OAuth2\Mapper\AbstractMapperFactory'
        ]
    ]
];
