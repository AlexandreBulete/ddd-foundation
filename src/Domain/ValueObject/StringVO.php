<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\ValueObject;

readonly class StringVO
{
    final public function __construct(
        protected string $value
    ) {
        $this->validate($value);
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    protected function validate(string $value): void
    {
        // Optional hook to override in subclasses
    }

    public function value(): string
    {
        return $this->value;
    }

    final public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

