<?php

namespace Aqilix\OAuth2\Entity;

use Aqilix\ORM\Entity\EntityInterface;

/**
 * OauthClients
 */
class OauthClients implements EntityInterface
{
    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $redirectUri;

    /**
     * @var string
     */
    private $grantTypes;

    /**
     * @var string
     */
    private $scope;

    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $clientId;


    /**
     * Set clientSecret
     *
     * @param string $clientSecret
     *
     * @return OauthClients
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * Get clientSecret
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Set redirectUri
     *
     * @param string $redirectUri
     *
     * @return OauthClients
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    /**
     * Get redirectUri
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * Set grantTypes
     *
     * @param string $grantTypes
     *
     * @return OauthClients
     */
    public function setGrantTypes($grantTypes)
    {
        $this->grantTypes = $grantTypes;

        return $this;
    }

    /**
     * Get grantTypes
     *
     * @return string
     */
    public function getGrantTypes()
    {
        return $this->grantTypes;
    }

    /**
     * Set scope
     *
     * @param string $scope
     *
     * @return OauthClients
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set userId
     *
     * @param string $userId
     *
     * @return OauthClients
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
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

    /**
     * Set clientId
     *
     * @return string
     */
    public function setClientId($clientId)
    {
        return $this->clientId = $clientId;
    }
}
