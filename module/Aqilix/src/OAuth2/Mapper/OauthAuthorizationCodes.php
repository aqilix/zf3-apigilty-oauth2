<?php

namespace Aqilix\OAuth2\Mapper;

use Aqilix\ORM\Mapper\AbstractMapper;
use Aqilix\ORM\Mapper\MapperInterface;

/**
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 *
 * OauthAuthorizationCodes Mapper
 */
class OauthAuthorizationCodes extends AbstractMapper implements MapperInterface
{
    /**
     * Get Entity Repository
     */
    public function getEntityRepository()
    {
        return $this->getEntityManager()->getRepository('Aqilix\OAuth2\Entity\OauthAuthorizationCodes');
    }
}
