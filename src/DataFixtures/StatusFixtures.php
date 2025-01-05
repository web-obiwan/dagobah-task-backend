<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatusFixtures extends Fixture
{
    /**
     * @var array<string>
     */
    private array $entities = [
         'Status'
    ];


    /**
     * @var array<mixed>
     */
    private array $statuses = [
        [
            'id' => 1,
            'name' => 'TODO',
        ],
        [
            'id' => 2,
            'name' => 'IN PROGRESS',
        ],
        [
            'id' => 3,
            'name' => 'CLOSED',
        ],
        [
            'id' => 4,
            'name' => 'REFUSED',
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
        foreach ($this->statuses as $row) {
            $status = new Status();
            $status->setId($row['id']);
            $status->setName($row['name']);
            $manager->persist($status);
            $this->addReference('status' . $status->getId(), $status);
        }
        $manager->flush();
    }
}
