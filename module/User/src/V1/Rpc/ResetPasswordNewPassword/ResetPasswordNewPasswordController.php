<?php
namespace User\V1\Rpc\ResetPasswordNewPassword;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Json\Json;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;
use ZF\Hal\View\HalJsonModel;

class ResetPasswordNewPasswordController extends AbstractActionController
{
    protected $confirmNewPasswordValidator;

    protected $resetPasswordService;

    public function __construct($confirmNewPasswordValidator, $resetPasswordService)
    {
        $this->setConfirmNewPasswordValidator($confirmNewPasswordValidator);
        $this->setResetPasswordService($resetPasswordService);
    }

    public function resetPasswordNewPasswordAction()
    {
        $this->getConfirmNewPasswordValidator()->setData(Json::decode($this->getRequest()->getContent(), true));
        try {
            $resetPassword = $this->getResetPasswordService();
            $resetPassword->reset($this->getConfirmNewPasswordValidator()->getValues());
            return new HalJsonModel([]);
        } catch (\Exception $e) {
            return new ApiProblemResponse(new ApiProblem(
                422,
                "Failed Validation",
                null,
                null,
                ['validation_messages' => ['resetPasswordKey' => [$e->getMessage()]]]
            ));
        }
    }

    /**
     * @return the $confirmNewPasswordValidator
     */
    public function getConfirmNewPasswordValidator()
    {
        return $this->confirmNewPasswordValidator;
    }

    /**
     * @param field_type $confirmNewPasswordValidator
     */
    public function setConfirmNewPasswordValidator($confirmNewPasswordValidator)
    {
        $this->confirmNewPasswordValidator = $confirmNewPasswordValidator;
    }

    /**
     * @return the $resetPasswordService
     */
    public function getResetPasswordService()
    {
        return $this->resetPasswordService;
    }

    /**
     * @param field_type $resetPasswordService
     */
    public function setResetPasswordService($resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }
}
