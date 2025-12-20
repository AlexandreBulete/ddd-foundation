<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\ValueObject;

use Symfony\Component\Uid\Ulid;

readonly class IdentifierVO implements IdentifierInterface
{
    final public function __construct(
        protected Ulid $value
    ) {
    }

    public static function generate(): static
    {
        return new static(new Ulid());
    }

    public static function fromString(string $value): static
    {
        return new static(Ulid::fromString($value));
    }

    public static function fromUlid(Ulid $ulid): static
    {
        return new static($ulid);
    }

    public function value(): Ulid
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value->toRfc4122();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function equals(self $other): bool
    {
        return $this->value->equals($other->value);
    }
}

