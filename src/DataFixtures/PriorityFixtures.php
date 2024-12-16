<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Priority;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PriorityFixtures extends Fixture
{
    /**
     * @var array<string>
     */
    private array $entities = [
        'Priority'
    ];


    /**
     * @var array<mixed>
     */
    private array $priorities = [
        [
            'id' => 1,
            'name' => 'Critical',
            'sort' => 1,
        ],
        [
            'id' => 2,
            'name' => 'Hight',
            'sort' => 2,
        ],
        [
            'id' => 3,
            'name' => 'Medium',
            'sort' => 3,
        ],
        [
            'id' => 4,
            'name' => 'Low',
            'sort' => 4,
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
        foreach ($this->priorities as $row) {
            $priority = new Priority();
            $priority->setId($row['id']);
            $priority->setName($row['name']);
            $priority->setSort($row['sort']);
            $manager->persist($priority);
            $this->addReference('priority' . $priority->getId(), $priority);
        }
        $manager->flush();
    }
}
