<?php
namespace User\V1\Service;

use User\V1\SignupEvent;
use Zend\EventManager\EventManagerAwareTrait;
use Aqilix\OAuth2\Mapper\OauthUsers as UserMapper;

class Signup
{
    use EventManagerAwareTrait;

    /**
     * @var \User\V1\SignupEvent
     */
    protected $signupEvent;

    public function __construct()
    {
    }

    /**
     * @return $signupEvent
     */
    public function getSignupEvent()
    {
        if ($this->signupEvent == null) {
            $this->signupEvent = new SignupEvent();
        }

        return $this->signupEvent;
    }

    /**
     * @param SignupEvent $signupEvent
     */
    public function setSignupEvent(SignupEvent $signupEvent)
    {
        $this->signupEvent = $signupEvent;
    }

    /**
     * Register new user
     *
     * @param array $signupData
     */
    public function register(array $signupData)
    {
        $this->getSignupEvent()->setSignupData($signupData);
        $insert = $this->getEventManager()->trigger(SignupEvent::EVENT_INSERT_USER, $this, $this->getSignupEvent());
        if ($insert->stopped()) {
            $this->getSignupEvent()->setException($insert->last());
            $insert = $this->getEventManager()->trigger(
                SignupEvent::EVENT_INSERT_USER_ERROR,
                $this,
                $this->getSignupEvent()
            );
            throw $this->getSignupEvent()->getException();
        } else {
            $this->getEventManager()->trigger(SignupEvent::EVENT_INSERT_USER_SUCCESS, $this, $this->getSignupEvent());
        }
    }
}
