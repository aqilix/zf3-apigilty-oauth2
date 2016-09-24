<?php
namespace Aqilix\Service\Mail;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

/**
 * Mailgun SMTP Transport Object
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
class MailgunAppFactory implements FactoryInterface
{
    /**
     * Create service for MailgunApp
     *
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\Factory\FactoryInterface::__invoke()
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config  = $container->get('Config')['mail']['transport']['mailgunapp'];
        $transport = new SmtpTransport();
        $options   = new SmtpOptions($config['options']);
        $transport->setOptions($options);

        return $transport;
    }
}
