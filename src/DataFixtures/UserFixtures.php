<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\RefreshToken;
use DateMalformedStringException;
use Doctrine\ORM\Mapping\ClassMetadata;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    /**
     * @var array<string>
     */
    private array $entities = [
        'User'
    ];


    /**
     * @var array<mixed>
     */
    private array $users = [
        // USER 1
        [
            'id' => 1,
            'email' => 'obiwan.kenobi@local.fr',
            'username' => 'obiwan',
            'plainPassword' => 'kenobi',
            'roles' => ['ROLE_SUPER_ADMIN'],
        ],
        [
            'id' => 2,
            'email' => 'luke.skywalker@local.fr',
            'username' => 'luke',
            'plainPassword' => 'skywalker',
            'roles' => ['ROLE_SUPER_ADMIN'],
        ],
    ];

    private array $refreshTokens = [
        [
            'refresh_token' => 'token',
            'username' => 'obiwan.kenobi@local.fr',
            'valid' => '2026-12-31',
        ],
    ];

    /**
     * @param ObjectManager $manager
     * @return void
     */
    private function updateGeneratorType(ObjectManager $manager): void
    {
        foreach ($this->entities as $entity) {
            $metadata = $manager->getClassMetaData("App\\Entity\\" . $entity);
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
        }
    }

    /**
     * @throws DateMalformedStringException
     */
    public function load(ObjectManager $manager): void
    {
        $this->updateGeneratorType($manager);
        $this->loadUsers($manager);
        $this->loadRefreshTokens($manager);
        $manager->flush();
    }


    /**
     * @param ObjectManager $manager
     * @return void
     */
    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->users as $row) {
            $user = new User();
            $user->setId($row['id']);
            $user->setEmail($row['email']);
            $user->setUsername($row['username']);
            $user->setPlainPassword($row['plainPassword']);
            $user->setRoles($row['roles']);
            $manager->persist($user);
            $this->addReference('user' . $user->getId(), $user);
        }
    }

    /**
     * @param ObjectManager $manager
     * @return void
     * @throws DateMalformedStringException
     */
    private function loadRefreshTokens(ObjectManager $manager): void
    {
        foreach ($this->refreshTokens as $row) {
            $refreshToken = new RefreshToken();
            $refreshToken->setRefreshToken($row['refresh_token']);
            $refreshToken->setUsername($row['username']);
            $refreshToken->setValid(new \DateTime($row['valid']));
            $manager->persist($refreshToken);
        }
    }
}
