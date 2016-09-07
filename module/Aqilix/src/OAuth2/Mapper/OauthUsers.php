<?php

namespace Aqilix\OAuth2\Mapper;

use Aqilix\ORM\Mapper\AbstractMapper;
use Aqilix\ORM\Mapper\MapperInterface;
use Zend\Crypt\Password\Bcrypt;

/**
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 *
 * OauthUsers Mapper
 */
class OauthUsers extends AbstractMapper implements MapperInterface
{
    protected $hashMethod;

    /**
     * Get Hash Object
     */
    public function getHashMethod()
    {
        if ($this->hashMethod == null) {
            $this->hashMethod = new Bcrypt();
            $this->hashMethod->setCost(10);
        }

        return $this->hashMethod;
    }

    /**
     * Set hash method
     *
     * @param unknown $hashMethod
     */
    public function setHashMethod($hashMethod)
    {
        $this->hashMethod = $hashMethod;
    }

    /**
     * Get Entity Repository
     */
    public function getEntityRepository()
    {
        return $this->getEntityManager()->getRepository('Aqilix\OAuth2\Entity\OauthUsers');
    }

    /**
     * Create Hash Password
     *
     * @param  string $password
     * @return string
     */
    public function getPasswordHash($password)
    {
        return $this->getHashMethod()->create($password);
    }

    /**
     * Verify password and hash password
     *
     * @param string $password
     * @param string $passwordHash
     *
     * @return boolean
     */
    public function verifyPassword($password, $passwordHash)
    {
        return $this->getHashMethod()->verify($password, $passwordHash);
    }
}
