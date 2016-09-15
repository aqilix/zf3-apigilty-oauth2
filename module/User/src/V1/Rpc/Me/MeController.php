<?php
namespace User\V1\Rpc\Me;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\Hal\View\HalJsonModel;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;

class MeController extends AbstractActionController
{
    private $userProfile;

    public function __construct($userProfile)
    {
        $this->userProfile = $userProfile;
    }

    public function meAction()
    {
        $userProfile = [];
        if (! is_null($this->userProfile)) {
            return new HalJsonModel(['uuid'  => $this->userProfile->getUuid()]);
        } else {
            return new ApiProblemResponse(new ApiProblem(404, "User Identity not found"));
        }
    }
}
