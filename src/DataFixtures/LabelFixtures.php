<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Label;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LabelFixtures extends Fixture
{
    /**
     * @var array<string>
     */
    private array $entities = [
         'Label'
    ];


    /**
     * @var array<mixed>
     */
    private array $labels = [
        [
            'id' => 1,
            'name' => 'Feature',
            'color' => '#00FF00',
        ],
        [
            'id' => 2,
            'name' => 'Bug',
            'color' => '#FF0000',
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
        foreach ($this->labels as $row) {
            $label = new Label();
            $label->setId($row['id']);
            $label->setName($row['name']);
            $label->setColor($row['color']);
            $manager->persist($label);
            $this->addReference('label' . $label->getId(), $label);
        }
        $manager->flush();
    }
}
