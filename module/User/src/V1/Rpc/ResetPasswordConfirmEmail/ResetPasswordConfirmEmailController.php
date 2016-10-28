<?php
namespace User\V1\Rpc\ResetPasswordConfirmEmail;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Json\Json;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;
use ZF\Hal\View\HalJsonModel;

class ResetPasswordConfirmEmailController extends AbstractActionController
{
    protected $confirmEmailValidator;

    protected $resetPasswordService;

    public function __construct($confirmEmailValidator, $resetPasswordService)
    {
        $this->setConfirmEmailValidator($confirmEmailValidator);
        $this->setResetPasswordService($resetPasswordService);
    }

    public function resetPasswordConfirmEmailAction()
    {
        $this->getConfirmEmailValidator()->setData(Json::decode($this->getRequest()->getContent(), true));
        try {
            $resetPassword = $this->getResetPasswordService();
            $resetPassword->create($this->getConfirmEmailValidator()->getValues());
            return new HalJsonModel([]);
        } catch (\Exception $e) {
            return new ApiProblemResponse(new ApiProblem(
                422,
                "Failed Validation",
                null,
                null,
                ['validation_messages' => ['emailAddress' => [$e->getMessage()]]]
            ));
        }
    }

    /**
     * @return $confirmEmailValidator
     */
    public function getConfirmEmailValidator()
    {
        return $this->confirmEmailValidator;
    }

    /**
     * @param field_type $confirmEmailValidator
     */
    public function setConfirmEmailValidator($confirmEmailValidator)
    {
        $this->confirmEmailValidator = $confirmEmailValidator;
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
