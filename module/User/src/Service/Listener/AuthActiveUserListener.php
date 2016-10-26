<?php
namespace User\Service\Listener;

use ZF\MvcAuth\MvcAuthEvent;
use Zend\Mvc\MvcEvent;
use ZF\MvcAuth\Identity\GuestIdentity;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;
use User\Mapper\UserProfile as UserProfileMapper;

class AuthActiveUserListener
{

    /**
     * @var \User\Mapper\UserProfile
     */
    protected $userProfileMapper;

    /**
     * Check activated
     *
     * @param  MvcAuthEvent
     */
    public function __invoke(MvcAuthEvent $mvcAuthEvent)
    {
        $identity = $mvcAuthEvent->getIdentity();
        if ($identity instanceof GuestIdentity) {
            return;
        }

        $username = $mvcAuthEvent->getIdentity()->getAuthenticationIdentity();
        if (! is_string($username)) {
            return;
        }

        $userProfile = $this->getUserProfileMapper()->fetchOneBy(['user' => $username]);
        if (! $userProfile->isActive()) {
            $response = new ApiProblemResponse(
                new ApiProblem(
                    401,
                    "Your account has not yet been activated. "
                    . "We have sent an email to " . $username . " "
                    . "Please check your inbox and click on the activation link "
                    . "to continue registration. If you do not see the email "
                    . "please check your Spam/Junk folder just in case"
                )
            );
            $mvcEvent = $mvcAuthEvent->getMvcEvent();
            $mvcResponse = $mvcEvent->getResponse();
            $mvcResponse->setStatusCode($response->getStatusCode());
            $mvcResponse->setHeaders($response->getHeaders());
            $mvcResponse->setContent($response->getContent());
            $mvcResponse->setReasonPhrase('Unauthorized');
            $em = $mvcEvent->getApplication()->getEventManager();
            $mvcEvent->setName(MvcEvent::EVENT_FINISH);
            $em->triggerEvent($mvcEvent);
            $mvcAuthEvent->stopPropagation();
            return $mvcResponse;
        }

        return;
    }

    /**
     * @return \User\Mapper\UserProfile
     */
    public function getUserProfileMapper()
    {
        return $this->userProfileMapper;
    }

    /**
     * Set UserProfile Mapper
     *
     * @param UserProfileMapper $userProfileMapper
     */
    public function setUserProfileMapper(UserProfileMapper $userProfileMapper)
    {
        $this->userProfileMapper = $userProfileMapper;
    }
}
