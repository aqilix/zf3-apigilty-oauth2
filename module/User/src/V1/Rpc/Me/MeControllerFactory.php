<?php
namespace User\V1\Rpc\Me;

class MeControllerFactory
{
    public function __invoke($controllers)
    {
        $authentication = $controllers->get('authentication');
        $email = $authentication->getIdentity()->getAuthenticationIdentity()['user_id'];
        $userProfile = $controllers->get('User\Mapper\UserProfile')->fetchOneBy(['user' => $email]);
        return new MeController($userProfile);
    }
}
