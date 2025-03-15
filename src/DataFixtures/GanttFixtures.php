<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Gantt;
use App\Entity\Project;
use App\Entity\Repository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GanttFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            ProjectFixtures::class,
            RepositoryFixtures::class
        ];
    }

    /**
     * @var array<string>
     */
    private array $entities = [
        'Gantt'
    ];


    /**
     * @var array<mixed>
     */
    private array $gantts = [
        [
            'id' => 1,
            'name' => 'tâche 1',
            'begunAt' => '2025-01-02',
            'project' => 1,
            'repositories' => [1,2],
            'parent_id' => null,
        ],
        [
            'id' => 2,
            'name' => 'Sous tâche 1',
            'begunAt' => '2025-01-02',
            'project' => 1,
            'repositories' => [1,2],
            'parent_id' => 1,
        ],
        [
            'id' => 3,
            'name' => 'Sous tâche 2',
            'begunAt' => '2025-01-02',
            'project' => 1,
            'repositories' => [1,2],
            'parent_id' => 1,
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
        foreach ($this->gantts as $row) {
            $gantt = new Gantt();
            $gantt->setId($row['id']);
            $gantt->setName($row['name']);
            $gantt->setProject($this->getReference('project' . $row['project'], Project::class));
            $gantt->setBegunAt(new \DateTime($row['begunAt']));
            foreach ($row['repositories'] as $repository) {
                $gantt->addRepository($this->getReference('repository' . $repository, Repository::class));
            }
            if (null !== $row['parent_id']) {
                $gantt->setParent($this->getReference('gantt' . $row['parent_id'], Gantt::class));
            }

            $manager->persist($gantt);
            $this->addReference('gantt' . $gantt->getId(), $gantt);
            $manager->flush();
        }
    }
}
