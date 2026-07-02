<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\ValueObject;

readonly class DatetimeVO
{
    final public function __construct(
        protected \DateTimeImmutable $value
    ) {
        $this->validate($value);
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

    protected function validate(\DateTimeImmutable $value): void
    {
        // Optional hook to override in subclasses (parity with StringVO).
    }

    public function equals(self $other): bool
    {
        return $this->value == $other->value;
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

