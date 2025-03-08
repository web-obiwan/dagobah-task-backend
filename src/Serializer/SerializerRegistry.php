<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\PropertyInfo\Extractor\ConstructorExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SerializerRegistry
{
    private static ?SerializerInterface $serializer = null;

    public static function getSerializer(): SerializerInterface
    {
        if (self::$serializer === null) {
            $phpDocExtractor = new PhpDocExtractor();
            $extractors = [
                new ConstructorExtractor([$phpDocExtractor]),
                new ReflectionExtractor(),
                $phpDocExtractor,
            ];
            $propertyTypeExtractor = new PropertyInfoExtractor($extractors, $extractors);

            self::$serializer = new Serializer(
                [
                    new DateTimeNormalizer(),
                    new JsonSerializableNormalizer(),
                    new ObjectNormalizer(null, null, null, $propertyTypeExtractor),
                    new ArrayDenormalizer(),
                ],
                [
                    new JsonEncoder(),
                ]
            );
        }

        return self::$serializer;
    }
}
