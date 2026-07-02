<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\ValueObject;

use AlexandreBulete\DddFoundation\Domain\ValueObject\LocaleCode;

final readonly class Translation
{
    public function __construct(
        private LocaleCode $locale,
        private string $value,
    ) {
        if (empty($value)) {
            throw new \InvalidArgumentException('Translation value cannot be empty');
        }
    }
    
    public static function create(string $locale, string $value): self
    {
        return new self(
            LocaleCode::fromString($locale),
            $value
        );
    }
    
    public function locale(): LocaleCode
    {
        return $this->locale;
    }
    
    public function value(): string
    {
        return $this->value;
    }
    
    public function isLocale(LocaleCode $locale): bool
    {
        return $this->locale->equals($locale);
    }
    
    public function equals(self $other): bool
    {
        return $this->locale->equals($other->locale) 
            && $this->value === $other->value;
    }
    
    public function toArray(): array
    {
        return [
            'locale' => $this->locale->value(),
            'value' => $this->value,
        ];
    }
}