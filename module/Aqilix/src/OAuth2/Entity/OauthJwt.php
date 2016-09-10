<?php

namespace Aqilix\OAuth2\Entity;

use Aqilix\ORM\Entity\EntityInterface;

/**
 * OauthJwt
 */
class OauthJwt implements EntityInterface
{
    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var string
     */
    private $clientId;


    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return OauthJwt
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set publicKey
     *
     * @param string $publicKey
     *
     * @return OauthJwt
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * Get publicKey
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Get clientId
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }
}
