<?php
namespace User\V1\Rpc\Signup;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\Hal\View\HalJsonModel;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;
use Zend\Json\Json;
use Aqilix\OAuth2\ResponseType\AccessToken;

class SignupController extends AbstractActionController
{
    protected $signupValidator;

    protected $signupService;

    public function __construct($signupService, $signupValidator)
    {
        $this->signupValidator = $signupValidator;
        $this->signupService   = $signupService;
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

        $this->signupService->register($this->signupValidator->getValues());
        $this->getResponse()->setStatusCode(201);
        return new HalJsonModel($this->signupService->getSignupEvent()->getAccessTokenResponse());
    }
}
