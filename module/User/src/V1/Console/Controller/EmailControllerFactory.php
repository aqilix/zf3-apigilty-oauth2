<?php
namespace User\V1\Console\Controller;

class EmailControllerFactory
{
    public function __invoke($controllers)
    {
        return new EmailController();
    }
}
