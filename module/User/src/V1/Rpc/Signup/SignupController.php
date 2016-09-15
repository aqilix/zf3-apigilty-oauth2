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
        try {
            $this->signupService->register($this->signupValidator->getValues());
            return new HalJsonModel($this->signupService->getSignupEvent()->getAccessTokenResponse());
        } catch (\Exception $e) {
            return new ApiProblemResponse(new ApiProblem(
                422,
                "Failed Validation",
                null,
                null,
                ['validation_messages' => ['email' => ['Email Address has been used']]]
            ));
        }
    }
}
