<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Handler;

use AlexandreBulete\DddFoundation\Application\Query\QueryInterface;
use AlexandreBulete\DddFoundation\Domain\Repository\RepositoryInterface;
use AlexandreBulete\DddFoundation\Application\Criteria\CriteriaNormalizerInterface;

abstract readonly class QueryCollectionHandler
{
    public function __construct(
        protected RepositoryInterface $repository,
        protected ?CriteriaNormalizerInterface $criteriaNormalizer = null
    ) {
        //
    }

    protected function build(QueryInterface $query): RepositoryInterface
    {
        $repository = $this->repository;

        if (!empty($query->criteria)) {
            $repository = $repository->filter(
                $this->normalize($query->criteria)
            );
        }

        if (null !== $query->page && null !== $query->itemsPerPage) {
            $repository = $repository->withPagination($query->page, $query->itemsPerPage);
        }

        if (!empty($query->withSorting)) {
            foreach ($query->withSorting as $field => $direction) {
                $repository = $repository->orderBy($field, $direction);
            }
        }

        return $repository;
    }


    protected function normalize(array $criteria): array
    {
        if (null !== $this->criteriaNormalizer) {
            return $this->criteriaNormalizer->normalize($criteria);
        }

        return $criteria;
    }
}

