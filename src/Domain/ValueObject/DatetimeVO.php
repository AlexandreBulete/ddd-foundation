<?php

declare(strict_types=1);

namespace Alexandrebulete\DddFoundation\Domain\ValueObject;

readonly class DatetimeVO
{
    final public function __construct(
        protected \DateTimeImmutable $value
    ) {
    }

    public static function now(): static
    {
        return new static(new \DateTimeImmutable());
    }

    public static function fromDateTime(\DateTimeImmutable $dateTime): static
    {
        return new static($dateTime);
    }

    public static function fromString(string $dateTime): static
    {
        try {
            return static::fromDateTime(new \DateTimeImmutable($dateTime));
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid date format: ' . $e->getMessage());
        }
    }

    public function value(): \DateTimeImmutable
    {
        return $this->value;
    }

    public function format(string $format = 'Y-m-d H:i:s'): string
    {
        return $this->value->format($format);
    }

    public function __toString(): string
    {
        return $this->format();
    }
}

