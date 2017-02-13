<?php
namespace User\V1\Notification\Email\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Psr\Log\LoggerAwareTrait;
use User\V1\ResetPasswordEvent;

class ResetPasswordEventListener extends AbstractListener implements ListenerAggregateInterface
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
            ResetPasswordEvent::EVENT_RESET_PASSWORD_CONFIRM_EMAIL_SUCCESS,
            [$this, 'sendResetPasswordKey'],
            400
        );
    }

    /**
     * Rund Console to Send Activation Email
     *
     * @param EventInterface $event
     */
    public function sendResetPasswordKey(ResetPasswordEvent $event)
    {
        $emailAddress = $event->getUserEntity()->getUsername();
        $resetPasswordKey = $event->getResetPasswordKey();
        // command: v1 user send-resetpassword-email <emailAddress> <resetPaswordKey>
        $cli = $this->phpProcessBuilder
                ->setArguments(['v1', 'user', 'send-resetpassword-email', $emailAddress, $resetPasswordKey])
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
    }
}
