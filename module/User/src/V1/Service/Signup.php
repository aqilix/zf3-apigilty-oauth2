<?php
namespace User\V1\Service;

use User\V1\SignupEvent;
use Zend\EventManager\EventManagerAwareTrait;

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
     * @param  array $signupData
     * @throw  \RuntimeException
     * @return void
     */
    public function register(array $signupData)
    {
        $this->getSignupEvent()->setSignupData($signupData);
        $signupEvent = $this->getSignupEvent();
        $signupEvent->setName(SignupEvent::EVENT_INSERT_USER);
        $insert = $this->getEventManager()->triggerEvent($signupEvent);
        if ($insert->stopped()) {
            $signupEvent->setException($insert->last());
            $signupEvent->setName(SignupEvent::EVENT_INSERT_USER_ERROR);
            $insert = $this->getEventManager()->triggerEvent($signupEvent);
            throw $this->getSignupEvent()->getException();
        } else {
            $signupEvent->setName(SignupEvent::EVENT_INSERT_USER_SUCCESS);
            $this->getEventManager()->triggerEvent($signupEvent);
        }
    }
}
