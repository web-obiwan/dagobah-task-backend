<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Repository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RepositoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            ProjectFixtures::class,
        ];
    }

    /**
     * @var array<string>
     */
    private array $entities = [
        'Repository'
    ];


    /**
     * @var array<mixed>
     */
    private array $repositories = [
        [
            'id' => 1,
            'name' => 'Backoffice',
            'project' => 1,
            'gitlab_id' => 1,
        ],
        [
            'id' => 2,
            'name' => 'Frontoffice',
            'project' => 1,
            'gitlab_id' => 2,
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

    public function load(ObjectManager $manager): void
    {
        $this->updateGeneratorType($manager);
        foreach ($this->repositories as $row) {
            $repository = new Repository();
            $repository->setId($row['id']);
            $repository->setName($row['name']);
            $repository->setProject($this->getReference('project' . $row['project'], Project::class));
            $repository->setGitlabId($row['gitlab_id']);
            $manager->persist($repository);
            $this->addReference('repository' . $repository->getId(), $repository);
            $manager->flush();
        }
    }
}
