<?php
namespace User\V1\Rpc\Signup;

class SignupControllerFactory
{
    public function __invoke($controllers)
    {
        return new SignupController();
    }
}
