<?php
namespace User\V1\Notification\Email\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Psr\Log\LoggerAwareTrait;
use User\V1\SignupEvent;

class SignupEventListener extends AbstractListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    use LoggerAwareTrait;

    /**
     * (non-PHPdoc)
     * @see \Zend\EventManager\ListenerAggregateInterface::attach()
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            SignupEvent::EVENT_INSERT_USER_SUCCESS,
            [$this, 'sendWelcomeEmail'],
            499
        );
    }

    /**
     * Run Console to Send Activation Email
     *
     * @param  EventInterface $event
     * @return int
     */
    public function sendWelcomeEmail(SignupEvent $event)
    {
        $emailAddress = $event->getUserEntity()->getUsername();
        $userActivationKey = $event->getUserActivationKey();
        // command: v1 user send-welcome-email <emailAddress> <activationCode>
        $cli = $this->phpProcessBuilder
                ->setArguments(['v1', 'user', 'send-welcome-email', $emailAddress, $userActivationKey])
                ->getProcess();
        $cli->start();
        $pid = $cli->getPid();
        $this->logger->log(
            \Psr\Log\LogLevel::DEBUG,
            "{function} {pid} {commandline}",
            [
                "function" => __FUNCTION__,
                "commandline" => $cli->getCommandLine(),
                "pid" => $pid
            ]
        );
        return $pid;
    }
}
