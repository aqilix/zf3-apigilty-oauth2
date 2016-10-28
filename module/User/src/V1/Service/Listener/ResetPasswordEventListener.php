<?php
namespace User\V1\Service\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use User\V1\ResetPasswordEvent;
use User\Mapper\ResetPassword as ResetPasswordMapper;

class ResetPasswordEventListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @var \User\Mapper\ResetPassword
     */
    protected $resetPasswordMapper;

    /**
     * Constructor
     */
    public function __construct(ResetPasswordMapper $resetPasswordMapper)
    {
        $this->setResetPasswordMapper($resetPasswordMapper);
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            ResetPasswordEvent::EVENT_RESET_PASSWORD_CONFIRM_EMAIL,
            [$this, 'create'],
            499
        );
    }

    /**
     *  Ne
     *
     * @param  $event
     */
    public function create($event)
    {
        $mapper = $this->getResetPasswordMapper();
        // @todo retrieve expired from config
        $expiration = new \DateTime();
        $expiration->add(new \DateInterval('P14D'));
        try {
            $resetPassword = new \User\Entity\ResetPassword;
            $resetPassword->setUser($event->getUserEntity());
            $resetPassword->setExpiration($expiration);
            $this->getResetPasswordMapper()->save($resetPassword);
            $event->setResetPasswordEntity($resetPassword);
        } catch (\Exception $e) {
            $event->stopPropagation(true);
            return $e;
        }
    }

    /**
     * @return the $resetPasswordMapper
     */
    public function getresetPasswordMapper()
    {
        return $this->resetPasswordMapper;
    }

    /**
     * @param \User\Mapper\UserActivation $resetPasswordMapper
     */
    public function setresetPasswordMapper(ResetPasswordMapper $resetPasswordMapper)
    {
        $this->resetPasswordMapper = $resetPasswordMapper;
    }
}
