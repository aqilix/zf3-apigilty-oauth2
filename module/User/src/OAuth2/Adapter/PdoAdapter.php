<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2016 IsItUp.com
 * @author    Dolly Aswin <dolly.aswin@gmail.com>
 */

namespace User\OAuth2\Adapter;

use ZF\OAuth2\Adapter\PdoAdapter as ZFOAuth2PdoAdapter;
use ZF\MvcAuth\MvcAuthEvent;
use Zend\Authentication\Result;
use ZF\MvcAuth\Identity;
use Zend\EventManager\EventManager;

/**
 * Extension of OAuth2\Storage\PDO with EventManager
 */
class PdoAdapter extends ZFOAuth2PdoAdapter
{
    /**
     *
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var MvcAuthEvent
     */
    protected $mvcAuthEvent;

    public function __construct($connection, $config)
    {
        parent::__construct($connection, $config);
    }

    /**
     * Check password using bcrypt
     *
     * @param string $user
     * @param string $password
     * @return bool
     */
    protected function checkPassword($user, $password)
    {
        $this->getMvcAuthEvent()->setAuthenticationResult(new Result(Result::SUCCESS, $user['user_id']));
        $result = $this->getMvcAuthEvent()->getAuthenticationResult();
        $this->getMvcAuthEvent()->setIdentity(new Identity\AuthenticatedIdentity($result->getIdentity()));
        $this->getMvcAuthEvent()->setName(MvcAuthEvent::EVENT_AUTHENTICATION);
        $this->getEventManager()->triggerEvent($this->getMvcAuthEvent());
        $verified = parent::verifyHash($password, $user['password']);
        if (! $verified) {
            $this->getMvcAuthEvent()->setAuthenticationResult(new Result(Result::FAILURE_CREDENTIAL_INVALID, null));
            $this->getMvcAuthEvent()->setIdentity(new Identity\GuestIdentity());
        }

        $this->getMvcAuthEvent()->setName(MvcAuthEvent::EVENT_AUTHENTICATION_POST);
        $this->getEventManager()->triggerEvent($this->getMvcAuthEvent());
        return $verified;
    }

    /**
     * @return MvcAuthEvent
     */
    public function getMvcAuthEvent()
    {
        return $this->mvcAuthEvent;
    }

    /**
     * @param MvcAuthEvent $mvcAuthEvent
     */
    public function setMvcAuthEvent(MvcAuthEvent $mvcAuthEvent)
    {
        $this->mvcAuthEvent = $mvcAuthEvent;
    }

    /**
     * @return the $eventManager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @param EventManager $eventManager
     */
    public function setEventManager(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }
}
