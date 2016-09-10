<?php
namespace User\V1\Rpc\Signup;

class SignupControllerFactory
{
    public function __invoke($controllers)
    {
        $signupValidator = $controllers->get('InputFilterManager')->get('User\\V1\\Rpc\\Signup\\Validator');
        $signup = $controllers->get('user.signup');
        return new SignupController($signup, $signupValidator);
    }
}
