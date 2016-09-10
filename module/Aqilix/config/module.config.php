<?php
return [
    'service_manager' => [
        'invokables' => [
            'oauth2.accessToken' => 'Aqilix\OAuth2\ResponseType\AccessToken'
        ],
        'abstract_factories' => [
            'Aqilix\OAuth2\Mapper\AbstractMapperFactory'
        ]
    ]
];
