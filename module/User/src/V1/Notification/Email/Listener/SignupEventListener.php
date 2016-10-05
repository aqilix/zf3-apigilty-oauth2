<?php
namespace User\V1\Notification\Email\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\View\Model\ViewModel;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Message as MailMessage;
use User\V1\SignupEvent;

class SignupEventListener extends AbstractListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $welcomeMailMessage;

    protected $phpProcessBuilder;

    public function __construct($phpProcessBuilder)
    {
        $this->phpProcessBuilder = $phpProcessBuilder;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            SignupEvent::EVENT_INSERT_USER_SUCCESS,
            [$this, 'sendWelcomeEmail'],
            499
        );
    }

    public function getWelcomeMessage($event)
    {
        $view = new ViewModel([
            'contactUsUrl'  => '',
            'activationUrl' => $this->getConfig()['activation_url']
        ]);
        $view->setTemplate('user/email/welcome.phtml');
        $html = $this->getViewRenderer()->render($view);
        return $html;
    }

    /**
     * Send Activation Email
     *
     * @param EventInterface $event
     */
    public function sendWelcomeEmail($event)
    {
        $emailAddress = $event->getParams()->getUserEntity()->getUsername();
        file_put_contents('/tmp/cli.txt', get_class($this->phpProcessBuilder));
        $cli = $this->phpProcessBuilder
                ->setArguments(['v1 user send-welcome-email ' . $emailAddress . ' ABCDEFG'])
                ->getProcess();
        file_put_contents('/tmp/cli.txt', $cli->getCommandline());
        $output = $cli->run();
        file_put_contents('/tmp/output.txt', $output);
    }

    /**
     * @return the $welcomeMailMessage
     */
    public function getWelcomeMailMessage()
    {
        return $this->welcomeMailMessage;
    }

    /**
     * @param Zend\Mail\Message $welcomeMailMessage
     */
    public function setWelcomeMailMessage(MailMessage $welcomeMailMessage)
    {
        $this->welcomeMailMessage = $welcomeMailMessage;
    }
}
