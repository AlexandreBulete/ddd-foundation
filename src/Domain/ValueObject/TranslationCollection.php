<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\ValueObject;

use Countable;
use IteratorAggregate;
use ArrayIterator;

final readonly class TranslationCollection implements Countable, IteratorAggregate
{
    /** @param Translation[] $translations */
    private array $translations;
    
    public function __construct(array $translations = [])
    {
        // Index par locale pour accès rapide
        $indexed = [];
        foreach ($translations as $translation) {
            if (!$translation instanceof Translation) {
                throw new \InvalidArgumentException('All items must be Translation instances');
            }
            $indexed[$translation->locale()->value()] = $translation;
        }
        $this->translations = $indexed;
    }
    
    public static function fromArray(array $data): self
    {
        $translations = [];
        foreach ($data as $locale => $value) {
            if (!empty($value)) {
                $translations[] = Translation::create($locale, $value);
            }
        }
        return new self($translations);
    }
    
    public function add(Translation $translation): self
    {
        $translations = $this->translations;
        $translations[$translation->locale()->value()] = $translation;
        return new self(array_values($translations));
    }
    
    public function remove(LocaleCode $locale): self
    {
        $translations = $this->translations;
        unset($translations[$locale->value()]);
        return new self(array_values($translations));
    }
    
    public function get(LocaleCode $locale): ?Translation
    {
        return $this->translations[$locale->value()] ?? null;
    }
    
    public function in(LocaleCode $locale): ?string
    {
        return $this->get($locale)?->value();
    }
    
    public function inOrFallback(LocaleCode $locale, ?LocaleCode $fallback = null): ?string
    {
        $value = $this->in($locale);
        if ($value !== null) {
            return $value;
        }
        
        $fallback ??= LocaleCode::default();
        return $this->in($fallback);
    }
    
    public function has(LocaleCode $locale): bool
    {
        return isset($this->translations[$locale->value()]);
    }
    
    public function isEmpty(): bool
    {
        return empty($this->translations);
    }
    
    public function count(): int
    {
        return count($this->translations);
    }
    
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator(array_values($this->translations));
    }
    
    public function locales(): array
    {
        return array_keys($this->translations);
    }
    
    public function toArray(): array
    {
        $result = [];
        foreach ($this->translations as $locale => $translation) {
            $result[$locale] = $translation->value();
        }
        return $result;
    }
}