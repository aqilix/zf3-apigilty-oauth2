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
        if (file_exists($value)) {
            $photo = "http://apitest.aqilix.com/profile/photo/" . basename($value);
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
        if (is_array($value) || isset($value['tmp_name'])) {
            $photo = $value['tmp_name'];
        }

        return $photo;
    }
}
