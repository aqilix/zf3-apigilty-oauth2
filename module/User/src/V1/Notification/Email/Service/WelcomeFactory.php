<?php
namespace User\V1\Notification\Email\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Mail\Message;

/**
 * Message Object For Notification Email
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
class WelcomeFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config  = $container->get('Config')['user']['email']['welcome'];
        $message = new Message();
        $message->addFrom($config['from'], $config['name'])
            ->setSubject($config['subject']);
        return $message;
    }
}
