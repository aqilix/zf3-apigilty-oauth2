<?php
namespace User\V1\Rest\Profile;

class ProfileResourceFactory
{
    public function __invoke($services)
    {
        $userProfileMapper = $services->get('User\Mapper\UserProfile');
        $userProfile = $services->get('user.profile');
        return new ProfileResource($userProfileMapper, $userProfile);
    }
}
