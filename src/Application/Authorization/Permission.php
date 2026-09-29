<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Authorization;

/**
 * On a command or a query: the permission it requires, when the derived one
 * will not do (ADR 0008).
 *
 * By default a use case's permission is derived from its class —
 * `Client\Application\Query\FindClients\FindClientsQuery` requires
 * `client.find_clients`. Declare it to:
 *
 * - keep it stable across a class rename (roles store it in the database);
 * - group several use cases under one box to tick
 *   (`FindClients` and `ShowClient` → `client.read`).
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class Permission
{
    public function __construct(
        public string $id,
    ) {
        if (preg_match('/^[a-z][a-z0-9_]*(\.[a-z][a-z0-9_]*)+$/', $id) !== 1) {
            throw new \InvalidArgumentException(sprintf('Invalid permission "%s": expected dotted snake_case, like "client.read".', $id));
        }
    }
}
