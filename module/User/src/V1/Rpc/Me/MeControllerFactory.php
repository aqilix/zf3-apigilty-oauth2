<?php
namespace User\V1\Rpc\Me;

class MeControllerFactory
{
    public function __invoke($controllers)
    {
        // get authentication service
        $authentication = $controllers->get('authentication');
        // get identity from authentication
        $identity = $authentication->getIdentity();
        $email    = $identity->getAuthenticationIdentity()['user_id'];
        // retrieve user based on authentication identity
        $userMapper = $controllers->get('Aqilix\OAuth2\Mapper\OauthUsers');
        $user = $userMapper->fetchOneBy(['username' => $email]);
        // retrieve user profile mapper
        $userProfileMapper = $controllers->get('User\Mapper\UserProfile');
        $userProfile = $userProfileMapper->fetchOneBy(['user' => $user->getUsername()]);
        return new MeController($userProfile);
    }
}
