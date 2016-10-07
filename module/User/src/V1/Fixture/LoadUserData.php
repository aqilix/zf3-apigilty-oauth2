<?php
namespace User\V1\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Aqilix\OAuth2\Entity\OauthUsers;
use Zend\Crypt\Password\Bcrypt;

// class LoadUserData implements FixtureInterface
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 0;
    }

    public function setServiceLocator($sl)
    {
        $this->serviceLocator = $sl;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function load(ObjectManager $manager)
    {
        $bcrypt   = new Bcrypt();
        $password = $bcrypt->create('12345678');

        $userData = [
            [
                'username'  => 'dolly.aswin@aqilix.com',
                'password'  => $password,
                'firstName' => 'Dolly Aswin',
                'lastName'  => 'Harahap'
            ]
        ];

        foreach ($userData as $key => $data) {
            $user[$key] = new OauthUsers();
            $user[$key]->setUsername($data['username']);
            $user[$key]->setPassword($data['password']);
            $user[$key]->setFirstName($data['firstName']);
            $user[$key]->setLastName($data['lastName']);
            $manager->persist($user[$key]);
        }

        $manager->flush();
        foreach ($userData as $key => $data) {
            $this->addReference('user' . $key, $user[$key]);
        }
    }
}
