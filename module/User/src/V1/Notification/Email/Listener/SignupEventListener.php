<?php
namespace User\V1\Notification\Email\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Symfony\Component\Process\ProcessBuilder;
use User\V1\SignupEvent;

class SignupEventListener extends AbstractListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $phpProcessBuilder;

    /**
     * Construct Event
     *
     * @param ProcessBuilder $phpProcessBuilder
     */
    public function __construct(ProcessBuilder $phpProcessBuilder)
    {
        $this->setPhpProcessBuilder($phpProcessBuilder);
    }

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
     * Rund Console to Send Activation Email
     *
     * @param EventInterface $event
     */
    public function sendWelcomeEmail($event)
    {
        $emailAddress = $event->getParams()->getUserEntity()->getUsername();
        // command: v1 user send-welcome-email <emailAddress> <activationCode>
        $cli = $this->phpProcessBuilder
                ->setArguments(['v1', 'user', 'send-welcome-email', $emailAddress, 'ABCDEFG'])
                ->getProcess();
        $cli->start();
        $pid = $cli->getPid();
    }

    /**
     * Get ProcessBuilder
     *
     * @return ProcessBuilder $phpProcessBuilder
     */
    public function getPhpProcessBuilder()
    {
        return $this->phpProcessBuilder;
    }

    /**
     * @param ProcessBuilder $phpProcessBuilder
     */
    public function setPhpProcessBuilder(ProcessBuilder $phpProcessBuilder)
    {
        $this->phpProcessBuilder = $phpProcessBuilder;
    }
}
