<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Sprint;
use App\Entity\Status;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SprintFixtures extends Fixture
{
    /**
     * @var array<string>
     */
    private array $entities = [
         'Sprint'
    ];


    /**
     * @var array<mixed>
     */
    private array $sprints = [
        [
            'id' => 1,
            'name' => 'Sprint 1',
            'begunAt' => '2024-01-06',
            'endedAt' => '2024-01-12',
        ],
        [
            'id' => 2,
            'name' => 'Sprint 2',
            'begunAt' => '2024-01-13',
            'endedAt' => '2024-01-19',
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
        foreach ($this->sprints as $row) {
            $sprint = new Sprint();
            $sprint->setId($row['id']);
            $sprint->setName($row['name']);
            $sprint->setBegunAt(new \DateTime($row['begunAt']));
            $sprint->setEndedAt(new \DateTime($row['endedAt']));
            $manager->persist($sprint);
            $this->addReference('sprint' . $sprint->getId(), $sprint);
        }
        $manager->flush();
    }
}
