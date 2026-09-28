<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Handler;

use AlexandreBulete\DddFoundation\Application\Query\QueryInterface;
use AlexandreBulete\DddFoundation\Domain\Exception\EntityNotFoundException;
use AlexandreBulete\DddFoundation\Domain\Repository\RepositoryInterface;
use AlexandreBulete\DddFoundation\Domain\ValueObject\IdentifierVO;

/**
 * Loads one entity by the public `id` the query declares (an IdentifierVO).
 *
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
     * @param QueryInterface<mixed> $query
     *
     * @return T
     *
     * @throws EntityNotFoundException
     */
    protected function build(QueryInterface $query): object
    {
        $entity = $this->repository->findById(self::idOf($query));

        if (null === $entity) {
            throw $this->throw($query);
        }

        return $entity;
    }

    /**
     * @param QueryInterface<mixed> $query
     */
    protected function throw(QueryInterface $query): \Throwable
    {
        return new EntityNotFoundException($this->repository::class, self::idOf($query));
    }

    /**
     * @param QueryInterface<mixed> $query
     */
    private static function idOf(QueryInterface $query): IdentifierVO
    {
        $id = get_object_vars($query)['id'] ?? null;
        if (!$id instanceof IdentifierVO) {
            throw new \InvalidArgumentException(sprintf('%s must declare a public IdentifierVO $id.', $query::class));
        }

        return $id;
    }
}
