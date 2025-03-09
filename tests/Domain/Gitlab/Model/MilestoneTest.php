<?php

namespace App\Tests\Domain\Gitlab\Model;

use App\Domain\Gitlab\Model\Milestone;
use App\Serializer\SerializerRegistry;
use PHPUnit\Framework\TestCase;

class MilestoneTest extends TestCase
{
    public function testSerializeMilestone(): void
    {
        $milestone = new Milestone();
        $milestone->setId(1);
        $milestone->setTitle('Title');
        $milestone->setDescription('Description');
        $milestone->setDueDate('2020-01-01');
        $milestone->setStartDate('2020-01-02');

        $serializer = SerializerRegistry::getSerializer();
        $serialized = $serializer->serialize($milestone, 'json');

        /** @var Milestone $deserialized */
        $deserialized = $serializer->deserialize($serialized, Milestone::class, 'json');

        $this->assertEquals(1, $deserialized->getId());
        $this->assertEquals('Title', $deserialized->getTitle());
        $this->assertEquals('Description', $deserialized->getDescription());
        $this->assertEquals('2020-01-01', $deserialized->getDueDate());
        $this->assertEquals('2020-01-02', $deserialized->getStartDate());
    }
}
