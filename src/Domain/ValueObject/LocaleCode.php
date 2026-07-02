<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\ValueObject;

use AlexandreBulete\DddFoundation\Domain\ValueObject\StringVO;

/**
 * TODO: custom allowed locales
 */
readonly class LocaleCode extends StringVO
{
    private const ALLOWED_LOCALES = [
        'fr', 
        'en', 
        'es'
    ];
    
    protected function validate(string $value): void
    {
        if (!in_array($value, self::ALLOWED_LOCALES, true)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid locale "%s". Allowed: %s', $value, implode(', ', self::ALLOWED_LOCALES))
            );
        }
    }
    
    public static function french(): self
    {
        return new self('fr');
    }
    
    public static function english(): self
    {
        return new self('en');
    }
    
    public static function spanish(): self
    {
        return new self('es');
    }
    
    public static function default(): self
    {
        return self::french();
    }
}