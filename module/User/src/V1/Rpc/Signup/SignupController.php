<?php
namespace User\V1\Rpc\Signup;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\Hal\View\HalJsonModel;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;

class SignupController extends AbstractActionController
{
    public function signupAction()
    {
        if (true) {
            $this->getResponse()->setStatusCode(201);
            return new HalJsonModel([
                'access_token' => '',
                'expires_in' => 3600,
                'token_type' => 'Bearer',
                'scope' => null,
                'refresh_token' => ''
            ]);
        } else {
            return new ApiProblemResponse(new ApiProblem(500, "Tests"));
        }
    }
}
