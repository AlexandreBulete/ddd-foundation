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
│   └── ValueObject/
│       ├── DatetimeVO.php
│       ├── IdentifierInterface.php
│       ├── IdentifierVO.php
│       └── StringVO.php
├── Application/
│   ├── Command/
│   │   ├── AsCommandHandler.php
│   │   ├── CommandBusInterface.php
│   │   └── CommandInterface.php
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

## Related Packages

### Bridges (pure PHP, no framework dependency)
- `alexandrebulete/ddd-doctrine-bridge` - Doctrine ORM integration
- `alexandrebulete/ddd-apiplatform-bridge` - API Platform integration

### Bundles (Symfony integration)
- `alexandrebulete/ddd-symfony-bundle` - Symfony Messenger integration
- `alexandrebulete/ddd-doctrine-bundle` - Doctrine bundle for Symfony
- `alexandrebulete/ddd-apiplatform-bundle` - API Platform bundle for Symfony
- `alexandrebulete/ddd-sylius-bundle` - Sylius Stack integration

