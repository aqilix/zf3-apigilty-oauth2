<?php
return [
    'controllers' => [
        'factories' => [
            'User\\V1\\Rpc\\Signup\\Controller' => \User\V1\Rpc\Signup\SignupControllerFactory::class,
            'User\\V1\\Rpc\\Me\\Controller' => \User\V1\Rpc\Me\MeControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            'user.signup' => \User\V1\Service\SignupFactory::class,
            'user.profile' => \User\V1\Service\ProfileFactory::class,
            'user.signup.listener' => \User\V1\Service\Listener\SignupEventListenerFactory::class,
            'user.profile.listener' => \User\V1\Service\Listener\ProfileEventListenerFactory::class,
            'user.notification.email.signup.listener' => \User\V1\Notification\Email\Listener\SignupEventListenerFactory::class,
            'user.notification.email.service.welcome' => \User\V1\Notification\Email\Service\WelcomeFactory::class,
            \User\V1\Rest\Profile\ProfileResource::class => \User\V1\Rest\Profile\ProfileResourceFactory::class,
            \User\V1\Hydrator\Strategy\PhotoStrategy::class => \User\V1\Hydrator\Strategy\PhotoStrategyFactory::class
        ],
        'abstract_factories' => [
            0 => \User\Mapper\AbstractMapperFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            'User\\Hydrator\\UserProfile' => \User\V1\Hydrator\UserProfileHydratorFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
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
            'user.rpc.me' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/me',
                    'defaults' => [
                        'controller' => 'User\\V1\\Rpc\\Me\\Controller',
                        'action' => 'me',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'user.rpc.signup',
            1 => 'user.rest.profile',
            2 => 'user.rpc.me',
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
        'User\\V1\\Rpc\\Me\\Controller' => [
            'service_name' => 'Me',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'user.rpc.me',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'User\\V1\\Rpc\\Signup\\Controller' => 'Json',
            'User\\V1\\Rest\\Profile\\Controller' => 'HalJson',
            'User\\V1\\Rpc\\Me\\Controller' => 'Json',
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
            'User\\V1\\Rpc\\Me\\Controller' => [
                0 => 'application/json',
                1 => 'application/vnd.aqilix.bootstrap.v1+json',
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
                3 => 'multipart/form-data',
            ],
            'User\\V1\\Rpc\\Me\\Controller' => [
                0 => 'application/json',
                1 => 'application/vnd.aqilix.bootstrap.v1+json',
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
                            'format' => 'Y-m-d',
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'dateOfBirth',
                'description' => 'Date Of Birth',
                'field_type' => 'String',
                'error_message' => 'Date not valid',
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
                        'name' => \Zend\Filter\StripNewlines::class,
                        'options' => [],
                    ],
                ],
                'name' => 'country',
                'description' => 'Country',
            ],
            8 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\File\Extension::class,
                        'options' => [
                            'extension' => 'png, jpg, jpeg',
                            'message' => 'File extension not match',
                        ],
                    ],
                    1 => [
                        'name' => \Zend\Validator\File\MimeType::class,
                        'options' => [
                            'mimeType' => 'image/png, image/jpeg',
                            'message' => 'File type extension not match',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\File\RenameUpload::class,
                        'options' => [
                            'use_upload_extension' => true,
                            'randomize' => true,
                            'target' => 'data/photo',
                        ],
                    ],
                ],
                'name' => 'photo',
                'description' => 'Photo',
                'field_type' => 'File',
                'type' => \Zend\InputFilter\FileInput::class,
                'error_message' => 'Photo is not valid',
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
                0 => 'GET',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \User\Entity\UserProfile::class,
            'collection_class' => \User\V1\Rest\Profile\ProfileCollection::class,
            'service_name' => 'Profile',
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \User\Entity\UserProfile::class => [
                'entity_identifier_name' => 'uuid',
                'route_name' => 'user.rest.profile',
                'route_identifier_name' => 'profile_id',
                'hydrator' => 'User\\Hydrator\\UserProfile',
            ],
            \User\V1\Rest\Profile\ProfileCollection::class => [
                'entity_identifier_name' => 'uuid',
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
            'User\\V1\\Rpc\\Me\\Controller' => [
                'actions' => [
                    'Me' => [
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ],
                ],
            ],
        ],
    ],
];
