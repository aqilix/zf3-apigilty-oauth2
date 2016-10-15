<?php
namespace User\V1\Rpc\UserActivation;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\Hal\View\HalJsonModel;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;
use Zend\Json\Json;

class UserActivationController extends AbstractActionController
{
    protected $userActivationValidator;

    protected $userActivationService;

    public function __construct($userActivationService, $userActivationValidator)
    {
        $this->userActivationService   = $userActivationService;
        $this->userActivationValidator = $userActivationValidator;
    }

    public function activationAction()
    {
        $this->userActivationValidator->setData(Json::decode($this->getRequest()->getContent(), true));
        try {
            $this->userActivationService->activate($this->userActivationValidator->getValues());
//             $this->userActivationService->getUserActivationEvent()->getUserActivationEntity();
//             $this->userActivationService->getUserActivationEvent()->getUserProfileEntity();
            print_r($this->userActivationService->getUserActivationEvent()->getUserProfileEntity()->getUuid());
            exit;
//             return new HalJsonModel($this->userActivationService->getUserActivationEvent()->getUserActivationEntity());
//             return new HalJsonModel($this->signupService->getSignupEvent()->getAccessTokenResponse());
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
            return new ApiProblemResponse(new ApiProblem(
                422,
                "Failed Validation",
                null,
                null,
                ['validation_messages' => ['activationUuid' => ['Invalid Activatino UUID']]]
            ));
        }
    }
}
