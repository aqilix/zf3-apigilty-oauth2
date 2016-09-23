<?php
namespace User\V1\Rest\Profile;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use ZF\ApiProblem\ApiProblemResponse;
use User\Mapper\UserProfile as UserProfileMapper;
use User\V1\Service\Profile as UserProfileService;

class ProfileResource extends AbstractResourceListener
{
    protected $userProfileMapper;

    protected $userProfileService;

    public function __construct(UserProfileMapper $userProfileMapper, UserProfileService $userProfileService)
    {
        $this->setUserProfileMapper($userProfileMapper);
        $this->setUserProfileService($userProfileService);
    }

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        return new ApiProblem(405, 'The POST method has not been defined');
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        $userProfile = $this->getUserProfileMapper()->fetchOneBy(['uuid' => $id]);
        if (is_null($userProfile)) {
            return new ApiProblemResponse(new ApiProblem(404, "User Profile not found"));
        }

        return $userProfile;
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        return new ApiProblem(405, 'The GET method has not been defined for collections');
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resource');
    }

    /**
     * Patch (partial in-place update) a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patchList($data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for collections');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        $userProfile = $this->getUserProfileMapper()->fetchOneBy(['uuid' => $id]);
        if (is_null($userProfile)) {
            return new ApiProblemResponse(new ApiProblem(404, "User Profile not found"));
        }

        $inputFilter = $this->getInputFilter();
        $this->getUserProfileService()->update($userProfile, $inputFilter);
        return $userProfile;
    }

    /**
     * @return the $userProfileMapper
     */
    public function getUserProfileMapper()
    {
        return $this->userProfileMapper;
    }

    /**
     * @param UserProfileMapper $userProfileMapper
     */
    public function setUserProfileMapper(UserProfileMapper $userProfileMapper)
    {
        $this->userProfileMapper = $userProfileMapper;
    }

    /**
     * @return the $userProfileService
     */
    public function getUserProfileService()
    {
        return $this->userProfileService;
    }

    /**
     * @param UserProfileService $userProfileService
     */
    public function setUserProfileService(UserProfileService $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }
}
