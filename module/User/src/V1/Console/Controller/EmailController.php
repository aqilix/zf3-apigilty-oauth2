<?php
namespace User\V1\Console\Controller;

use RuntimeException;
use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mail\Transport\TransportInterface;
use Zend\View\Model\ViewModel;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Message as MailMessage;
use Zend\View\Renderer\RendererInterface as RendererInterface;
use Psr\Log\LoggerAwareTrait;

class EmailController extends AbstractConsoleController
{
    use LoggerAwareTrait;

    /**
     * @var MailMessage
     */
    protected $welcomeMailMessage;

    /**
     * @var MailMessage
     */
    protected $activationMailMessage;

    /**
     * @var MailMessage
     */
    protected $resetPasswordMailMessage;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var unknown
     */
    protected $viewRenderer;

    /**
     * @var TransportInterface
     */
    protected $mailTransport;

    /**
     * @param RendererInterface  $viewRenderer
     * @param TransportInterface $mailTransport
     * @param array $config
     */
    public function __construct(
        RendererInterface $viewRenderer,
        TransportInterface $mailTransport,
        array $config = []
    ) {
        $this->setMailTransport($mailTransport);
        $this->setViewRenderer($viewRenderer);
        $this->setConfig($config);
    }

    /**
     * Send Welcome Email
     *
     * @throws RuntimeException
     */
    public function sendWelcomeEmailAction()
    {
        $request = $this->getRequest();
        if (! $request instanceof ConsoleRequest) {
            throw new RuntimeException('You can only use this action from a console!');
        }

        // get email address
        $emailAddress   = $request->getParam('emailAddress');
        // get activation code
        $activationCode = $request->getParam('activationCode');
        $view = new ViewModel([
            'contactUsUrl'  => $this->getConfig()['contact_us'],
            'activationUrl' => str_replace(':code', $activationCode, $this->getConfig()['activation_url'])
        ]);
        $view->setTemplate('user/email/welcome.phtml');
        $html = $this->getViewRenderer()->render($view);
        $htmlMimePart = new MimePart($html);
        $htmlMimePart->setType('text/html');
        $mimeMessage  = new MimeMessage();
        $mimeMessage->addPart($htmlMimePart);
        $mailMessage = $this->getWelcomeMailMessage();
        $mailMessage->addTo($emailAddress);
        $mailMessage->setBody($mimeMessage);
        $mail = $this->getMailTransport();
        $mail->send($mailMessage);
        $this->logger->log(
            \Psr\Log\LogLevel::DEBUG,
            "{function} {emailAddress}",
            [
                "function" => __FUNCTION__,
                "emailAddress" => $emailAddress,
            ]
        );
    }

    /**
     * Send Activation Email
     *
     * @throws RuntimeException
     */
    public function sendActivationEmailAction()
    {
        $request = $this->getRequest();
        if (! $request instanceof ConsoleRequest) {
            throw new RuntimeException('You can only use this action from a console!');
        }

        // get email address
        $emailAddress = $request->getParam('emailAddress');
        // get activation code
        $view = new ViewModel(['contactUsUrl'  => $this->getConfig()['contact_us']]);
        $view->setTemplate('user/email/activation.phtml');
        $html = $this->getViewRenderer()->render($view);
        $htmlMimePart = new MimePart($html);
        $htmlMimePart->setType('text/html');
        $mimeMessage  = new MimeMessage();
        $mimeMessage->addPart($htmlMimePart);
        $mailMessage = $this->getActivationMailMessage();
        $mailMessage->addTo($emailAddress);
        $mailMessage->setBody($mimeMessage);
        $mail = $this->getMailTransport();
        $mail->send($mailMessage);
    }

    /**
     * Send Activation Email
     *
     * @throws RuntimeException
     */
    public function sendResetPasswordEmailAction()
    {
        $request = $this->getRequest();
        if (! $request instanceof ConsoleRequest) {
            throw new RuntimeException('You can only use this action from a console!');
        }

        // get email address
        $emailAddress = $request->getParam('emailAddress');
        // get resetPassword code
        $resetPasswordKey = $request->getParam('resetPasswordKey');
        $view = new ViewModel([
            'contactUsUrl' => $this->getConfig()['contact_us'],
            'resetPasswordUrl' => str_replace(':code', $resetPasswordKey, $this->getConfig()['reset_password_url'])
        ]);
        $view->setTemplate('user/email/resetpassword.phtml');
        $html = $this->getViewRenderer()->render($view);
        $htmlMimePart = new MimePart($html);
        $htmlMimePart->setType('text/html');
        $mimeMessage  = new MimeMessage();
        $mimeMessage->addPart($htmlMimePart);
        $mailMessage = $this->getResetPasswordMailMessage();
        $mailMessage->addTo($emailAddress);
        $mailMessage->setBody($mimeMessage);
        $mail = $this->getMailTransport();
        $mail->send($mailMessage);
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

    /**
     * @return the $activationMailMessage
     */
    public function getActivationMailMessage()
    {
        return $this->activationMailMessage;
    }

    /**
     * @param \Zend\Mail\Message $activationMailMessage
     */
    public function setActivationMailMessage($activationMailMessage)
    {
        $this->activationMailMessage = $activationMailMessage;
    }

    /**
     * @return the $resetPasswordMailMessage
     */
    public function getResetPasswordMailMessage()
    {
        return $this->resetPasswordMailMessage;
    }

    /**
     * @param \Zend\Mail\Message $resetPasswordMailMessage
     */
    public function setResetPasswordMailMessage($resetPasswordMailMessage)
    {
        $this->resetPasswordMailMessage = $resetPasswordMailMessage;
    }

    /**
     * @return the $viewRenderer
     */
    public function getViewRenderer()
    {
        return $this->viewRenderer;
    }

    /**
     * @param field_type $viewRenderer
     */
    public function setViewRenderer($viewRenderer)
    {
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * @return the $mailTransport
     */
    public function getMailTransport()
    {
        return $this->mailTransport;
    }

    /**
     * @param field_type $mailTransport
     */
    public function setMailTransport(TransportInterface $mailTransport)
    {
        $this->mailTransport = $mailTransport;
    }

    /**
     * @return the $config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param field_type $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }
}
