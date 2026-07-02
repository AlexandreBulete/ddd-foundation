<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\Model;

use AlexandreBulete\DddFoundation\Domain\ValueObject\LocaleCode;
use AlexandreBulete\DddFoundation\Domain\ValueObject\TranslationCollection;

trait HasTranslations
{
    /**
     * Internal storage of translations
     * Structure: ['fieldName' => TranslationCollection]
     * 
     * @var array<string, TranslationCollection>
     */
    private array $_translations = [];
    
    /**
     * Current locale (injected by the Repository/Hydrator)
     */
    private ?LocaleCode $_currentLocale = null;
    
    /**
     * Generic access to a translation
     */
    public function translate(string $field, ?LocaleCode $locale = null): ?string
    {
        $locale ??= $this->_currentLocale ?? LocaleCode::default();
        
        if (!isset($this->_translations[$field])) {
            return null;
        }
        
        return $this->_translations[$field]->inOrFallback($locale);
    }
    
    /**
     * Retrieve all translations of a field
     */
    public function translations(string $field): TranslationCollection
    {
        return $this->_translations[$field] ?? new TranslationCollection();
    }
    
    /**
     * Define translations for a field
     */
    protected function setTranslations(string $field, TranslationCollection $translations): void
    {
        $this->_translations[$field] = $translations;
    }
    
    /**
     * Define translations from an array
     */
    protected function setTranslationsFromArray(string $field, array $data): void
    {
        $this->_translations[$field] = TranslationCollection::fromArray($data);
    }
    
    /**
     * Retrieve all translations (for persistence)
     */
    public function allTranslations(): array
    {
        return $this->_translations;
    }
    
    /**
     * Define the current locale (used by Repository)
     */
    public function setCurrentLocale(LocaleCode $locale): void
    {
        $this->_currentLocale = $locale;
    }
    
    /**
     * Magic getter for fluent syntax (optional)
     * 
     * Usage: $tech->name instead of $tech->translate('name')
     */
    public function __get(string $field): mixed
    {
        // If the field has translations, return the translation
        if (isset($this->_translations[$field])) {
            return $this->translate($field);
        }
        
        // Otherwise, default behavior (error)
        throw new \Exception(sprintf('Property "%s" not found', $field));
    }
    
    /**
     * Magic call for methods *In()
     * 
     * Usage: $tech->nameIn(LocaleCode::english())
     */
    public function __call(string $method, array $args): mixed
    {
        // Pattern: nameIn(), titleIn(), etc.
        if (preg_match('/^(\w+)In$/', $method, $matches)) {
            $field = lcfirst($matches[1]);
            $locale = $args[0] ?? null;
            
            if (!$locale instanceof LocaleCode) {
                throw new \InvalidArgumentException(
                    sprintf('%s() expects LocaleCode as first argument', $method)
                );
            }
            
            return $this->translate($field, $locale);
        }
        
        throw new \BadMethodCallException(sprintf('Method "%s" not found', $method));
    }
}