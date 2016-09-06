<?php
namespace User\V1\Rpc\Signup;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\Hal\View\HalJsonModel;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;
use Zend\Json\Json;

class SignupController extends AbstractActionController
{
    protected $signupValidator;

    public function __construct($signupValidator)
    {
        $this->signupValidator = $signupValidator;
    }

    /**
     * Handle /api/signup
     *
     * @return \ZF\ApiProblem\ApiProblemResponse|\ZF\Hal\View\HalJsonModel
     */
    public function signupAction()
    {
        $this->signupValidator->setData(Json::decode($this->getRequest()->getContent(), true));
        if ($this->signupValidator->isValid() === false) {
            foreach ($this->signupValidator->getInvalidInput() as $input) {
                $errors[$input->getName()] = $input->getMessages();
            }

            return new ApiProblemResponse(new ApiProblem(422, $errors));
        }

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
