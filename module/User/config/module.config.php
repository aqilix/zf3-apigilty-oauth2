<?php
return [
    'controllers' => [
        'factories' => [
            'User\\V1\\Rpc\\Signup\\Controller' => \User\V1\Rpc\Signup\SignupControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories'  => [
            'user.signup' => 'User\V1\Service\SignupFactory',
            'user.signup.listener' => 'User\V1\Service\Listener\SignupEventListenerFactory'
        ],
    ],
    'router' => [
        'routes' => [
            'user.rpc.signup' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/signup',
                    'defaults' => [
                        'controller' => 'User\\V1\\Rpc\\Signup\\Controller',
                        'action' => 'signup',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'user.rpc.signup',
        ],
    ],
    'zf-rpc' => [
        'User\\V1\\Rpc\\Signup\\Controller' => [
            'service_name' => 'Signup',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'user.rpc.signup',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'User\\V1\\Rpc\\Signup\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'User\\V1\\Rpc\\Signup\\Controller' => [
                0 => 'application/json',
                1 => 'application/vnd.aqilix.bootstrap.v1+json',
            ],
        ],
        'content_type_whitelist' => [
            'User\\V1\\Rpc\\Signup\\Controller' => [
                0 => 'application/json',
                1 => 'application/vnd.aqilix.bootstrap.v1.json',
            ],
        ],
    ],
    'zf-content-validation' => [
        'User\\V1\\Rpc\\Signup\\Controller' => [
            'input_filter' => 'User\\V1\\Rpc\\Signup\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'User\\V1\\Rpc\\Signup\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\EmailAddress::class,
                        'options' => [
                            'message' => 'Email Address Required',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'email',
                'description' => 'Email Address',
                'field_type' => 'email',
                'error_message' => 'Email Address Required',
            ],
            1 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\I18n\Validator\Alnum::class,
                        'options' => [
                            'message' => 'Password should contain alpha numeric string',
                        ],
                    ],
                    1 => [
                        'name' => \Zend\Validator\StringLength::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'name' => 'password',
                'description' => 'User Password',
                'field_type' => 'password',
                'error_message' => 'Password length at least 6 character with alphanumeric format',
            ],
        ],
    ],
];
