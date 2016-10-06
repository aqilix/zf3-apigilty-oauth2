<?php
namespace User\V1\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Aqilix\OAuth2\Entity\OauthScopes;

class LoadScopeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $scopeData = [
            [
                'scope' => 'read',
                'is_default' => 1
            ],
            [
                'scope' => 'like',
                'is_default' => 1
            ],
            [
                'scope' => 'comment',
                'is_default' => 1
            ],
            [
                'scope' => 'post',
                'is_default' => 1
            ],
            [
                'scope' => 'update',
                'is_default' => 1
            ],
            [
                'scope' => 'delete',
                'is_default' => 1
            ],
        ];

        foreach ($scopeData as $key => $data) {
            $scope[$key] = new OauthScopes();
            $scope[$key]->setScope($data['scope']);
            $scope[$key]->setIsDefault($data['is_default']);
            $manager->persist($scope[$key]);
        }

        $manager->flush();
        foreach ($scopeData as $key => $data) {
            $this->addReference('scope' . $key, $scope[$key]);
        }
    }
}
