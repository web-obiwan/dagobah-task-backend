<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture
{
    /**
     * @var array<string>
     */
    private array $entities = [
         'Project'
    ];


    /**
     * @var array<mixed>
     */
    private array $projects = [
        [
            'id' => 1,
            'name' => 'BLOG',
            'prefix' => 'BLOG'
        ],
        [
            'id' => 2,
            'name' => 'BACK OFFICE',
            'prefix' => 'BAKOFF'
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
        foreach ($this->projects as $row) {
            $project = new Project();
            $project->setId($row['id']);
            $project->setName($row['name']);
            $project->setPrefix($row['prefix']);
            $manager->persist($project);
            $this->addReference('project' . $project->getId(), $project);
        }
        $manager->flush();
    }
}
