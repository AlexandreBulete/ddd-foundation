<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\ValueObject;

readonly class StringVO
{
    protected string $value;

    final public function __construct(string $value)
    {
        $value = $this->normalize($value);
        $this->validate($value);
        $this->value = $value;
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    /**
     * Optional hook: canonical form of the value (trim, case…), applied before
     * validate() — so what is validated is exactly what is stored.
     */
    protected function normalize(string $value): string
    {
        return $value;
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

