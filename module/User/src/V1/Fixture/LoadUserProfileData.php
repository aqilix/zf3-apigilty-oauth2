<?php
namespace User\V1\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use User\Entity\UserProfile;
use User\Entity\User\Entity;

class LoadUserProfileData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 2;
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
        $userProfileData = [
            'firstName'  => 'Dolly Aswin',
            'lastName'   => 'Harahap',
            'address'    => 'Medan',
            'city' => 'Medan',
            'province'   => 'North Sumatera',
            'postalCode' => '20000',
            'country'    => 'ID',
            'user' => $this->getReference('user0')
        ];

        $userProfile = new UserProfile();
        $userProfile->setFirstName($userProfileData['firstName']);
        $userProfile->setLastName($userProfileData['lastName']);
        $userProfile->setAddress($userProfileData['address']);
        $userProfile->setCity($userProfileData['city']);
        $userProfile->setProvince($userProfileData['province']);
        $userProfile->setPostalCode($userProfileData['postalCode']);
        $userProfile->setCountry($userProfileData['country']);
        $userProfile->setUser($userProfileData['user']);
        $manager->persist($userProfile);
        $manager->flush();
    }
}
