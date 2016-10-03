<?php
namespace User\V1\Console\Controller;

use RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

class EmailController extends AbstractActionController
{

    public function __construct()
    {
    }

    public function sendWelcomeEmailAction()
    {
        $request = $this->getRequest();

        // Make sure that we are running in a console, and the user has not
        // tricked our application into running this action from a public web
        // server:
        if (! $request instanceof ConsoleRequest) {
            throw new RuntimeException('You can only use this action from a console!');
        }

        // Get user email from the console
        $emailAddress  = $request->getParam('emailAddress');
        $activationurl = $request->getParam('activationUrl');

        return "Email: " . $emailAddress . PHP_EOL . "Activation URL: " . $activationurl . PHP_EOL;
    }
}
