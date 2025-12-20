<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\Exception;

use AlexandreBulete\DddFoundation\Domain\ValueObject\IdentifierVO;

final class EntityNotFoundException extends \RuntimeException
{
    public function __construct(
        private readonly string $entityClass,
        private readonly IdentifierVO $id,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            sprintf('Cannot find entity %s with id %s', $this->entityClass, (string) $this->id),
            $code,
            $previous
        );
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getId(): IdentifierVO
    {
        return $this->id;
    }
}

