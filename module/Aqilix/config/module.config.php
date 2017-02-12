<?php
use Zend\Log\Logger;

return [
    "service_manager" => [
        "invokables" => [
            "oauth2.accessToken" => "Aqilix\OAuth2\ResponseType\AccessToken"
        ],
        "factories"  => [
            "Aqilix\Service\Mail" => \Aqilix\Service\Mail\MailgunAppFactory::class,
            "Aqilix\Service\PhpProcessBuilder" => \Aqilix\Service\PhpProcessFactory::class
        ],
        "abstract_factories" => [
            "Aqilix\OAuth2\Mapper\AbstractMapperFactory",
            "Zend\Log\LoggerAbstractServiceFactory",
        ],
        "delegators" => [
            "logger_default" => [
                Aqilix\Service\PsrLoggerDelegator::class
            ]
        ]
    ],
    "log" => [
        "logger_default" => [
            "writers" => [
                [
                    "name" => "stream",
                    "priority" => Logger::DEBUG,
                    "options" => [
                        'stream' => 'data/log/system.log',
                    ]
                ]
            ]
        ]
    ]
];
