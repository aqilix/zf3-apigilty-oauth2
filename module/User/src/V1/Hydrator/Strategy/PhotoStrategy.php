<?php

namespace User\V1\Hydrator\Strategy;

use Zend\Hydrator\Strategy\StrategyInterface;
use Aqilix\OAuth2\Entity\OauthUsers;

/**
 * Class UsernameStrategy
 *
 * @package User\Stdlib\Hydrator\Strategy
 */
class PhotoStrategy implements StrategyInterface
{
    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->setConfig($config);
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param  mixed $value The original value.
     * @param  object $object (optional) The original object for context.
     * @return mixed Returns the value that should be extracted.
     * @throws \RuntimeException If object os not a User
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function extract($value, $object = null)
    {
        $photo = null;
        if (! empty($value)) {
            $photo = $this->getConfig()['base_url'] . '/' . $this->getConfig()['bucket'] . '/' . basename($value);
        }

        return $photo;
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param  mixed $value The original value.
     * @param  array $data (optional) The original data for context.
     * @return mixed Returns the value that should be hydrated.
     * @throws \InvalidArgumentException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function hydrate($value, array $data = null)
    {
        $photo = null;
        if (is_array($value) && isset($value['tmp_name'])) {
            $photo = basename($value['tmp_name']);
        } else {
            $photo = basename($value);
        }

        return $photo;
    }

    /**
     * @return the $config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param field_type $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }
}
