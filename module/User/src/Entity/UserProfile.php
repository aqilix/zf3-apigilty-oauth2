<?php

namespace User\Entity;

use Aqilix\ORM\Entity\EntityInterface;
use Aqilix\OAuth2\Entity\OauthUsers;

/**
 * UserProfile
 */
class UserProfile implements EntityInterface
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var Aqilix\OAuth2\Entity\OauthUsers
     */
    private $user;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var DateTime
     */
    private $dateOfBirth;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $province;

    /**
     * @var string
     */
    private $postalCode;

    /**
     * @var string
     */
    private $country;

    /**
     * @return the $uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set User
     *
     * @param Aqilix\OAuth2\Entity\OauthUsers $oauthUser
     */
    public function setUser(OauthUsers $oauthUser)
    {
        $this->user = $oauthUser;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
}
