<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\DataTransformer;

use App\Domain\Gitlab\Model\Milestone;
use App\Entity\Sprint;
use Symfony\Component\Form\DataTransformerInterface;

class SprintToMilestoneTransformer implements DataTransformerInterface
{
    /**
     * @param Sprint $value
     * @return Milestone
     */
    public function transform($value): Milestone
    {
        $milestone = new Milestone();
        $milestone->setTitle($value->getName());
        //$milestone->setDescription($value->getDescription());
        $milestone->setStartDate($value->getBegunAt()->format('Y-m-d'));
        $milestone->setDueDate($value->getEndedAt()->format('Y-m-d'));
        return $milestone;
    }

    /**
     * @param $value
     * @return array<mixed>
     */
    public function reverseTransform($value): array
    {
        return [];
    }
}
