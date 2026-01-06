<?php 

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Criteria;

interface CriteriaNormalizerInterface
{
    public function normalize(array $criteria): array;
}