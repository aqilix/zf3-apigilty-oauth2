<?php

namespace User\Entity;

use Aqilix\ORM\Entity\EntityInterface;
use Aqilix\OAuth2\Entity\OauthUsers;
use Gedmo\Timestampable\Traits\Timestampable as TimestampableTrait;
use Gedmo\SoftDeleteable\Traits\SoftDeleteable as SoftDeleteableTrait;

/**
 * ResetPassword
 */
class ResetPassword implements EntityInterface
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
    private $reseted;

    /**
     * @var string
     */
    private $password;

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
     * @param \DateTime $expiration
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;
    }

    /**
     * @return the $reseted
     */
    public function getReseted()
    {
        return $this->reseted;
    }

    /**
     * @param \DateTime $reseted
     */
    public function setReseted($reseted)
    {
        $this->reseted = $reseted;
    }

    /**
     * @return the $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param \User\Entity\string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}
