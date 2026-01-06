<?php 

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Criteria;

abstract readonly class CriteriaNormalizer implements CriteriaNormalizerInterface
{
    /**
     * TODO: add criteria builder interface to the constructor
     */
    // public function __construct(
    //     protected readonly CriteriaBuilderInterface $criteriaBuilderInterface,
    // ) {
    // }

    public function normalize(array $criteria): array
    {
        return $this->dropEmptyValues($criteria);
    }

    protected function dropEmptyValues(array $criteria): array
    {
        foreach ($criteria as $key => $criterion) {
            $value = is_array($criterion) ? ($criterion['value'] ?? null) : $criterion;

            if ($value === null || $value === '') {
                unset($criteria[$key]);
            }
        }

        return $criteria;
    }

    protected function mergeCriteria(array $criteria, array $overrides): array
    {
        foreach ($overrides as $k => $v) {
            $criteria[$k] = $v;
        }

        return $criteria;
    }
}
