<?php
namespace User\V1\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Aqilix\OAuth2\Entity\OauthClients;
use Zend\Crypt\Password\Bcrypt;

class LoadClientData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 1;
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
        $bcrypt = new Bcrypt();
        $clientSecret = $bcrypt->create('client1234');
        $grantTypes   = [
          'mobile' => ['password', 'implicit', 'refresh_token'],
          'custom' => ['client_credentials', 'implicit', 'refresh_token'],
        ];
        $redirectUri  = '/oauth/receivecode';
        $clientData = [
            [
                'user'   => null,
                'secret' => $clientSecret,
                'client_id'  => 'ios',
                'grant_type' => $grantTypes['mobile'],
            ],
            [
                'user'   => $this->getReference('user0'),
                'secret' => $clientSecret,
                'client_id'  => 'android',
                'grant_type' => $grantTypes['mobile'],
            ],
            [
                'user'   => $this->getReference('user0'),
                'secret' => $clientSecret,
                'client_id'  => '55f94d92d97e5',
                'grant_type' => $grantTypes['custom'],
            ],
        ];

        foreach ($clientData as $key => $data) {
            $client[$key] = new OauthClients();
            $client[$key]->setClientSecret($data['secret']);
            $client[$key]->setClientId($data['client_id']);
            $client[$key]->setRedirectUri($redirectUri);
            $client[$key]->setGrantTypes(null);
            $manager->persist($client[$key]);
        }

        $manager->flush();
        foreach ($clientData as $key => $data) {
            $this->addReference('client' . $key, $client[$key]);
        }
    }
}
