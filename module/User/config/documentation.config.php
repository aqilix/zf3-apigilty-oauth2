<?php
return [
    'User\\V1\\Rpc\\Signup\\Controller' => [
        'description' => 'User Signup',
        'POST' => [
        'description' => 'User Signup Service. If signup successfully, it will return OAuth token. '
                      .  'So client can use this token to access the authorized resources',
            'request' => '{
   "email": "Email Address",
   "password": "User Password",
   "confirmPassword": "Confirm Password"
}',
            'response' => '{
  "access_token": "",
  "expires_in":,
  "token_type": "Bearer",
  "scope": null,
  "refresh_token": ""
}',
        ],
    ],
    'User\\V1\\Rest\\Profile\\Controller' => [
        'description' => 'REST API for User Profile',
    ],
];
