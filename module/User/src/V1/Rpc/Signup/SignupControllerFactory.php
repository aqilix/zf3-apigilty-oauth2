<?php
namespace User\V1\Rpc\Signup;

class SignupControllerFactory
{
    public function __invoke($controllers)
    {
        $signupValidator = $controllers->get('InputFilterManager')->get('User\\V1\\Rpc\\Signup\\Validator');
        return new SignupController($signupValidator);
    }
}
