<?php
return [
    'controllers' => [
        'factories' => [
            'User\\V1\\Rpc\\Signup\\Controller' => \User\V1\Rpc\Signup\SignupControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            'user.signup' => \User\V1\Service\SignupFactory::class,
            'user.signup.listener' => \User\V1\Service\Listener\SignupEventListenerFactory::class,
            \User\V1\Rest\Profile\ProfileResource::class => \User\V1\Rest\Profile\ProfileResourceFactory::class,
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
            'user.rest.profile' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/profile[/:profile_id]',
                    'defaults' => [
                        'controller' => 'User\\V1\\Rest\\Profile\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'user.rpc.signup',
            1 => 'user.rest.profile',
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
            'User\\V1\\Rest\\Profile\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'User\\V1\\Rpc\\Signup\\Controller' => [
                0 => 'application/json',
                1 => 'application/vnd.aqilix.bootstrap.v1+json',
            ],
            'User\\V1\\Rest\\Profile\\Controller' => [
                0 => 'application/hal+json',
                1 => 'application/json',
                2 => 'application/vnd.aqilix.bootstrap.v1+json',
            ],
        ],
        'content_type_whitelist' => [
            'User\\V1\\Rpc\\Signup\\Controller' => [
                0 => 'application/json',
                1 => 'application/vnd.aqilix.bootstrap.v1.json',
            ],
            'User\\V1\\Rest\\Profile\\Controller' => [
                0 => 'application/json',
                1 => 'application/vnd.aqilix.bootstrap.v1+json',
                2 => 'application/hal+json',
            ],
        ],
    ],
    'zf-content-validation' => [
        'User\\V1\\Rpc\\Signup\\Controller' => [
            'input_filter' => 'User\\V1\\Rpc\\Signup\\Validator',
        ],
        'User\\V1\\Rest\\Profile\\Controller' => [
            'input_filter' => 'User\\V1\\Rest\\Profile\\Validator',
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
        'User\\V1\\Rest\\Profile\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [
                            'charlist' => '!,@,#,$,%,^,&,*,(,),-,_,+,=,|,],},{,[,:,;,:',
                        ],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'firstName',
                'description' => 'First Name',
                'field_type' => 'String',
                'error_message' => 'First Name Required',
            ],
            1 => [
                'required' => false,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [
                            'charlist' => '!,@,#,$,%,^,&,*,(,),-,_,+,=,|,],},{,[,:,;,:',
                        ],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'lastName',
                'description' => 'Last Name',
                'field_type' => 'String',
            ],
            2 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\Date::class,
                        'options' => [
                            'format' => 'yyyy-mm-dd',
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'dateOfBirth',
                'description' => 'Date Of Birth',
                'field_type' => 'String',
                'error_message' => 'Date of Birth Required',
            ],
            3 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [
                            'charlist' => '!,@,#,$,%,^,&,*,(,),-,_,+,=,|,],},{,[,:,;,:',
                        ],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StripNewlines::class,
                        'options' => [],
                    ],
                ],
                'name' => 'address',
                'description' => 'Address',
                'error_message' => 'Address Required',
            ],
            4 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [
                            'charlist' => '!,@,#,$,%,^,&,*,(,),-,_,+,=,|,],},{,[,:,;,:',
                        ],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StripNewlines::class,
                        'options' => [],
                    ],
                ],
                'name' => 'city',
                'description' => 'City',
                'error_message' => 'City Required',
            ],
            5 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [
                            'charlist' => '!,@,#,$,%,^,&,*,(,),-,_,+,=,|,],},{,[,:,;,:',
                        ],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StripNewlines::class,
                        'options' => [],
                    ],
                ],
                'name' => 'province',
                'description' => 'Province',
                'error_message' => 'Province Required',
            ],
            6 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\I18n\Validator\PostCode::class,
                        'options' => [
                            'message' => 'Postal code should be 5 digit numeric characters',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\Digits::class,
                        'options' => [],
                    ],
                ],
                'name' => 'postalCode',
                'description' => 'Postal Code',
                'error_message' => 'Postal Code Required',
            ],
            7 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [
                            'charlist' => '!,@,#,$,%,^,&,*,(,),-,_,+,=,|,],},{,[,:,;,:',
                        ],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StripNewlines::class,
                        'options' => [],
                    ],
                ],
                'name' => 'country',
                'description' => 'Country',
            ],
        ],
    ],
    'zf-rest' => [
        'User\\V1\\Rest\\Profile\\Controller' => [
            'listener' => \User\V1\Rest\Profile\ProfileResource::class,
            'route_name' => 'user.rest.profile',
            'route_identifier_name' => 'profile_id',
            'collection_name' => 'profile',
            'entity_http_methods' => [
                0 => 'PATCH',
                1 => 'DELETE',
                2 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \User\V1\Rest\Profile\ProfileEntity::class,
            'collection_class' => \User\V1\Rest\Profile\ProfileCollection::class,
            'service_name' => 'Profile',
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \User\V1\Rest\Profile\ProfileEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.profile',
                'route_identifier_name' => 'profile_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \User\V1\Rest\Profile\ProfileCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.profile',
                'route_identifier_name' => 'profile_id',
                'is_collection' => true,
            ],
        ],
    ],
    'zf-mvc-auth' => [
        'authorization' => [
            'User\\V1\\Rest\\Profile\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => true,
                    'DELETE' => true,
                ],
            ],
        ],
    ],
];
