<?php
namespace User\V1\Rpc\UserActivation;

class UserActivationControllerFactory
{
    public function __invoke($controllers)
    {
        $userActivationValidator = $controllers->get('InputFilterManager')
                                        ->get('User\\V1\\Rpc\\UserActivation\\Validator');
        $userActivation = $controllers->get('user.activation');
        return new UserActivationController($userActivation, $userActivationValidator);
    }
}
