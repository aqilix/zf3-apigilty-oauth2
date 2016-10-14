<?php

namespace User\Entity;

use Aqilix\ORM\Entity\EntityInterface;
use Aqilix\OAuth2\Entity\OauthUsers;
use Gedmo\Timestampable\Traits\Timestampable as TimestampableTrait;
use Gedmo\SoftDeleteable\Traits\SoftDeleteable as SoftDeleteableTrait;

/**
 * UserProfile
 */
class UserActivation implements EntityInterface
{
    use TimestampableTrait;

    use SoftDeleteableTrait;

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var Aqilix\OAuth2\Entity\OauthUsers
     */
    private $user;

    /**
     * @var DateTime
     */
    private $expiration;

   /**
     * @var DateTime
     */
    private $activated;

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
     * @return the $expiration
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * @return the $activated
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * @param \DateTime $expiration
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;
    }

    /**
     * @param \DateTime $activated
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;
    }
}
