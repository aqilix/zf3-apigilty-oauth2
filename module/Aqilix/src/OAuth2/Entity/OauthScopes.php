<?php

namespace Aqilix\OAuth2\Entity;

use Aqilix\ORM\Entity\EntityInterface;

/**
 * OauthScopes
 */
class OauthScopes implements EntityInterface
{
    /**
     * @var string
     */
    private $type = 'supported';

    /**
     * @var string
     */
    private $scope;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var integer
     */
    private $isDefault;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set type
     *
     * @param string $type
     *
     * @return OauthScopes
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set scope
     *
     * @param string $scope
     *
     * @return OauthScopes
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
     * Set clientId
     *
     * @param string $clientId
     *
     * @return OauthScopes
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
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
     * Set isDefault
     *
     * @param integer $isDefault
     *
     * @return OauthScopes
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return integer
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
