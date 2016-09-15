<?php
namespace User\V1\Rest\Profile;

class ProfileResourceFactory
{
    public function __invoke($services)
    {
        return new ProfileResource();
    }
}
