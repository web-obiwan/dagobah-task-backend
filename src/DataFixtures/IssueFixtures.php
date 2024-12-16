<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Issue;
use App\Entity\Label;
use App\Entity\Priority;
use App\Entity\Project;
use App\Entity\Sprint;
use App\Entity\User;
use App\ValueObject\IssueStatus;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IssueFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            LabelFixtures::class,
            PriorityFixtures::class,
            ProjectFixtures::class,
            SprintFixtures::class,
            UserFixtures::class,
        ];
    }

    /**
     * @var array<string>
     */
    private array $entities = [
         'Issue'
    ];


    /**
     * @var array<mixed>
     */
    private array $issues = [
        [
            'id' => 1,
            'name' => 'Bug',
            'owner' => 1,
            'reviewer' => 2,
            'project' => 1,
            'sprint' => 1,
            'status' => IssueStatus::BACKLOG,
            'priority' => 1,
            'labels' => [2]
        ],
        [
            'id' => 2,
            'name' => 'Feature',
            'owner' => 1,
            'reviewer' => 2,
            'project' => 1,
            'sprint' => 1,
            'status' => IssueStatus::BACKLOG,
            'priority' => 2,
            'labels' => [1]
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
        foreach ($this->issues as $row) {
            $issue = new Issue();
            $issue->setId($row['id']);
            $issue->setName($row['name']);
            $issue->setOwner($this->getReference('user' . $row['owner'], User::class));
            $issue->setReviewer($this->getReference('user' . $row['reviewer'], User::class));
            $issue->setStatus($row['status']);
            $issue->setPriority($this->getReference('priority' . $row['priority'], Priority::class));
            $issue->setProject($this->getReference('project' . $row['project'], Project::class));
            $issue->setSprint($this->getReference('sprint' . $row['sprint'], Sprint::class));
            foreach ($row['labels'] as $label) {
                $issue->addLabel($this->getReference('label' . $label, Label::class));
            }
            $manager->persist($issue);
            $this->addReference('issue.' . $issue->getId(), $issue);
            $manager->flush();
        }
    }
}
