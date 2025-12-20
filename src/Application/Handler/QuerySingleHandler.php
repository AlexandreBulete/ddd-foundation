<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Handler;

use AlexandreBulete\DddFoundation\Application\Query\QueryInterface;
use AlexandreBulete\DddFoundation\Domain\Exception\EntityNotFoundException;
use AlexandreBulete\DddFoundation\Domain\Repository\RepositoryInterface;

/**
 * @template T of object
 */
abstract readonly class QuerySingleHandler
{
    /**
     * @param RepositoryInterface<T> $repository
     */
    public function __construct(
        protected RepositoryInterface $repository
    ) {
    }

    /**
     * @return T
     *
     * @throws EntityNotFoundException
     */
    protected function build(QueryInterface $query): object
    {
        $entity = $this->repository->findById($query->id);

        if (null === $entity) {
            throw $this->throw($query);
        }

        return $entity;
    }

    protected function throw(QueryInterface $query): \Throwable
    {
        return new EntityNotFoundException(get_class($this->repository), $query->id);
    }
}

