# DDD Foundation

Pure PHP DDD Foundation for building Domain-Driven Design projects. This package provides the core building blocks: Domain layer (ValueObjects, Repository interfaces, Exceptions), Application layer (Command/Query/Handler), and InMemory infrastructure for testing.

## Installation

```bash
composer require alexandrebulete/ddd-foundation
```

## Structure

```
src/
├── Domain/
│   ├── Exception/
│   │   └── EntityNotFoundException.php
│   ├── Repository/
│   │   ├── PaginatorInterface.php
│   │   └── RepositoryInterface.php
│   ├── Trait/
│   │   └── AsSelectableEnum.php
│   └── ValueObject/
│       ├── DatetimeVO.php
│       ├── EmailVO.php
│       ├── IdentifierInterface.php
│       ├── IdentifierVO.php
│       └── StringVO.php
├── Application/
│   ├── Command/
│   │   ├── AsCommandHandler.php
│   │   ├── CommandBusInterface.php
│   │   └── CommandInterface.php
│   ├── Criteria/
│   │   ├── CriteriaBuilder.php
│   │   ├── CriteriaBuilderInterface.php
│   │   ├── CriteriaNormalizer.php
│   │   └── CriteriaNormalizerInterface.php
│   ├── Handler/
│   │   ├── QueryCollectionHandler.php
│   │   └── QuerySingleHandler.php
│   └── Query/
│       ├── AsQueryHandler.php
│       ├── QueryBusInterface.php
│       └── QueryInterface.php
└── Infrastructure/
    └── InMemory/
        ├── InMemoryPaginator.php
        └── InMemoryRepository.php
```

## Usage

### Value Objects

```php
use AlexandreBulete\DddFoundation\Domain\ValueObject\IdentifierVO;
use AlexandreBulete\DddFoundation\Domain\ValueObject\StringVO;

// Generate a new identifier
$id = IdentifierVO::generate();

// Create from string
$id = IdentifierVO::fromString('01HZXYZ...');

// String value object
$title = StringVO::fromString('My Title');
```

### AsSelectableEnum Trait

A trait for PHP enums that provides a `choices()` method, useful for form select fields or grid filters:

```php
use AlexandreBulete\DddFoundation\Domain\Trait\AsSelectableEnum;

enum StatusEnum: string
{
    use AsSelectableEnum;

    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
}

// Usage
StatusEnum::choices();
// Returns: ['draft' => 'draft', 'published' => 'published', 'archived' => 'archived']
```

Perfect for Sylius Grid filters or Symfony form choices:

```php
// Sylius Grid
->addFilter(SelectFilter::create('status', StatusEnum::choices()))

// Symfony Form
->add('status', ChoiceType::class, [
    'choices' => array_flip(StatusEnum::choices()),
])
```

### Repository Interface

```php
use AlexandreBulete\DddFoundation\Domain\Repository\RepositoryInterface;

class PostRepository implements RepositoryInterface
{
    // Implement the interface methods
}
```

### Command/Query Bus

```php
use AlexandreBulete\DddFoundation\Application\Command\CommandInterface;
use AlexandreBulete\DddFoundation\Application\Command\AsCommandHandler;

// Define a command
readonly class CreatePostCommand implements CommandInterface
{
    public function __construct(
        public string $title,
        public string $content,
    ) {}
}

// Define a handler
#[AsCommandHandler]
readonly class CreatePostHandler
{
    public function __invoke(CreatePostCommand $command): void
    {
        // Handle the command
    }
}
```

### Criteria System

The Criteria system provides a standardized way to build and normalize query criteria for filtering data. It consists of two main components:

#### CriteriaBuilder

Provides fluent methods to build typed criteria arrays:

```php
use AlexandreBulete\DddFoundation\Application\Criteria\CriteriaBuilder;

$builder = new CriteriaBuilder();

// Equality
$builder->eq('status', 'published');      // ['status' => ['type' => 'eq', 'value' => 'published']]
$builder->neq('status', 'draft');         // ['status' => ['type' => 'neq', 'value' => 'draft']]

// Comparison
$builder->lt('price', 100);               // ['price' => ['type' => 'lt', 'value' => 100]]
$builder->lte('price', 100);              // ['price' => ['type' => 'lte', 'value' => 100]]
$builder->gt('price', 50);                // ['price' => ['type' => 'gt', 'value' => 50]]
$builder->gte('price', 50);               // ['price' => ['type' => 'gte', 'value' => 50]]

// Collections
$builder->in('category', ['a', 'b']);     // ['category' => ['type' => 'in', 'value' => ['a', 'b']]]
$builder->notIn('category', ['c']);       // ['category' => ['type' => 'notIn', 'value' => ['c']]]

// Pattern matching
$builder->like('title', '%keyword%');     // ['title' => ['type' => 'like', 'value' => '%keyword%']]
$builder->notLike('title', '%spam%');     // ['title' => ['type' => 'notLike', 'value' => '%spam%']]
```

#### CriteriaNormalizer

Abstract class to normalize and transform raw criteria before passing them to repositories. Extend this class to add domain-specific normalization logic:

```php
use AlexandreBulete\DddFoundation\Application\Criteria\CriteriaNormalizer;
use AlexandreBulete\DddFoundation\Application\Criteria\CriteriaNormalizerInterface;

final readonly class PostCriteriaNormalizer extends CriteriaNormalizer implements CriteriaNormalizerInterface
{
    public function normalize(array $criteria): array
    {
        // First call parent to drop empty values
        $criteria = parent::normalize($criteria);

        // Then apply domain-specific transformations
        $criteria = $this->normalizeStatusCriteria($criteria);

        return $criteria;
    }

    private function normalizeStatusCriteria(array $criteria): array
    {
        $status = $criteria['status'] ?? null;

        if (!is_string($status)) {
            return $criteria;
        }

        $now = new \DateTimeImmutable();

        return match ($status) {
            'published' => $this->mergeCriteria($criteria, [
                'status' => ['type' => 'eq', 'value' => 'published'],
                'publishedAt' => ['type' => 'lte', 'value' => $now],
            ]),
            'scheduled' => $this->mergeCriteria($criteria, [
                'status' => ['type' => 'eq', 'value' => 'published'],
                'publishedAt' => ['type' => 'gt', 'value' => $now],
            ]),
            default => $criteria,
        };
    }
}
```

The base `CriteriaNormalizer` class provides:
- `normalize(array $criteria): array` - Entry point, drops empty values by default
- `dropEmptyValues(array $criteria): array` - Removes null or empty string values
- `mergeCriteria(array $criteria, array $overrides): array` - Merges override criteria into existing criteria

## Related Packages

### Bridges (pure PHP, no framework dependency)
- `alexandrebulete/ddd-doctrine-bridge` - Doctrine ORM integration
- `alexandrebulete/ddd-apiplatform-bridge` - API Platform integration

### Bundles (Symfony integration)
- `alexandrebulete/ddd-symfony-bundle` - Symfony Messenger integration
- `alexandrebulete/ddd-doctrine-bundle` - Doctrine bundle for Symfony
- `alexandrebulete/ddd-apiplatform-bundle` - API Platform bundle for Symfony
- `alexandrebulete/ddd-sylius-bundle` - Sylius Stack integration

