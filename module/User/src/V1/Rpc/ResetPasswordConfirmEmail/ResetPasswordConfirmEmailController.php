<?php
namespace User\V1\Rpc\ResetPasswordConfirmEmail;

use Zend\Mvc\Controller\AbstractActionController;
use Aqilix\OAuth2\Mapper\OauthUsers as OauthUsersMapper;
use Aqilix\OAuth2\Entity\OauthUsers as OauthUsersEntity;
use Zend\Json\Json;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;
use ZF\Hal\View\HalJsonModel;

class ResetPasswordConfirmEmailController extends AbstractActionController
{
    protected $confirmEmailValidator;

    protected $resetPasswordService;

    protected $oauthUsersMapper;

    public function __construct($confirmEmailValidator, $resetPasswordService, $oauthUsersMapper)
    {
        $this->setConfirmEmailValidator($confirmEmailValidator);
        $this->setOauthUsersMapper($oauthUsersMapper);
        $this->setResetPasswordService($resetPasswordService);
    }

    public function resetPasswordConfirmEmailAction()
    {
        $this->getConfirmEmailValidator()->setData(Json::decode($this->getRequest()->getContent(), true));
        $emailAddress = $this->getConfirmEmailValidator()->getValue('emailAddress');
        $user = $this->getOauthUsersMapper()->fetchOneBy(['username' => $emailAddress]);
        if (! $user instanceof OauthUsersEntity) {
            $emailAddress = $this->getConfirmEmailValidator()->getValue('emailAddress');
            $user = $this->getOauthUsersMapper()->fetchOneBy(['username' => $emailAddress]);
            if (! $user instanceof OauthUsersEntity) {
                return new ApiProblemResponse(new ApiProblem(
                    404,
                    "Failed Validation",
                    null,
                    null,
                    ['validation_messages' => ['emailAddress' => ['Email Address Not Found']]]
                ));
            }
        }

        try {
            $resetPassword = $this->getResetPasswordService();
            $resetPassword->create($user);
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

    /**
     * @return the $oauthUsersMapper
     */
    public function getOauthUsersMapper()
    {
        return $this->oauthUsersMapper;
    }

    /**
     * @param OauthUsers $oauthUsersMapper
     */
    public function setOauthUsersMapper(OauthUsersMapper $oauthUsersMapper)
    {
        $this->oauthUsersMapper = $oauthUsersMapper;
    }
}
