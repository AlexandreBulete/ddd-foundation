<?php

declare(strict_types=1);

namespace Alexandrebulete\DddFoundation\Application\Handler;

use Alexandrebulete\DddFoundation\Application\Query\QueryInterface;
use Alexandrebulete\DddFoundation\Domain\Exception\EntityNotFoundException;
use Alexandrebulete\DddFoundation\Domain\Repository\RepositoryInterface;

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

