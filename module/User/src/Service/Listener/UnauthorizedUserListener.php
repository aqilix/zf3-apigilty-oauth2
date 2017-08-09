<?php
namespace User\Service\Listener;

use Zend\Mvc\MvcEvent;

class UnauthorizedUserListener
{
    /**
     * Check response with 401 status code
     *
     * @param  MvcEvent
     */
    public function __invoke(MvcEvent $mvcEvent)
    {
        $mvcResponse = $mvcEvent->getResponse();
        if ($mvcResponse instanceof \Zend\Http\Response && $mvcResponse->getStatusCode() === \Zend\Http\Response::STATUS_CODE_401) {
            $mvcResponse->getHeaders()->addHeaderLine('Www-Authenticate', 'Bearer realm="Service"');
        }

        return;
    }
}
