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
        return 2;
    }

    public function load(ObjectManager $manager)
    {
        $bcrypt = new Bcrypt();
        $clientSecret = $bcrypt->create('123456');
        $grantTypes   = [
          'mobile' => ['password', 'implicit', 'refresh_token'],
          'custom' => ['client_credentials', 'implicit', 'refresh_token'],
        ];
        $redirectUri  = '/oauth/receivecode';
        $clientCredentialScope = [
                                    $this->getReference('scope0'),
                                    $this->getReference('scope1'),
                                    $this->getReference('scope2')
                                 ];

        $clientData = [
            [
                'user'   => null,
                'secret' => $clientSecret,
                'client_id'  => 'mobile',
                'grant_type' => $grantTypes['mobile'],
            ],
            [
                'user'   => $this->getReference('user0'),
                'secret' => $clientSecret,
                'client_id'  => '55f94d5ee7707',
                'grant_type' => $grantTypes['custom'],
                'scope'  => $clientCredentialScope
            ],
            [
                'user'   => $this->getReference('user0'),
                'secret' => $clientSecret,
                'client_id'  => '55f94d92d97e5',
                'grant_type' => $grantTypes['custom'],
                'scope'  => $clientCredentialScope
            ],
        ];

        foreach ($clientData as $key => $data) {
            $client[$key] = new OauthClients();
            $client[$key]->setUser($data['user']);
            $client[$key]->setSecret($data['secret']);
            $client[$key]->setClientId($data['client_id']);
            $client[$key]->setRedirectUri($redirectUri);
            $client[$key]->setGrantType($data['grant_type']);
            if (isset($data['scope'])) {
                foreach ($data['scope'] as $scope) {
                    $client[$key]->addScope($scope);
                    $scope->addClient($client[$key]);
                    $manager->persist($scope);
                }
            }

            $manager->persist($client[$key]);
        }

        $manager->flush();
        foreach ($clientData as $key => $data) {
            $this->addReference('client' . $key, $client[$key]);
        }
    }
}
