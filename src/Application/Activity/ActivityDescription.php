<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Activity;

/**
 * What a message says about itself in the activity journal.
 *
 * - `subjectType` / `subjectId`: what it touches ("mission", "42") — what the
 *   journal filters on;
 * - `summary` / `summaryParams`: a human sentence, as a translation key;
 * - `details`: the facts worth keeping, picked explicitly.
 *
 * Values are scalars (or lists of scalars) on purpose: nothing is serialized
 * implicitly, so no entity, secret or client document can slip in unnoticed.
 */
final readonly class ActivityDescription
{
    /**
     * @param array<string, scalar|null>                     $summaryParams
     * @param array<string, scalar|list<scalar|null>|null>   $details
     */
    public function __construct(
        public ?string $subjectType = null,
        public ?string $subjectId = null,
        public ?string $summary = null,
        public array $summaryParams = [],
        public array $details = [],
    ) {
        if (($subjectType === null) !== ($subjectId === null)) {
            throw new \InvalidArgumentException('A subject needs both a type and an id.');
        }

        // The types above guide static analysis; this is the guarantee for
        // callers that do not run it.
        self::assertFacts($summaryParams, $details);
    }

    /**
     * @param array<mixed> $summaryParams
     * @param array<mixed> $details
     */
    private static function assertFacts(array $summaryParams, array $details): void
    {
        foreach ($summaryParams as $key => $value) {
            if (!self::isScalarOrNull($value)) {
                throw new \InvalidArgumentException(sprintf('Summary parameter "%s" must be a scalar.', $key));
            }
        }

        foreach ($details as $key => $value) {
            if (self::isScalarOrNull($value)) {
                continue;
            }
            if (!is_array($value) || !array_is_list($value)) {
                throw new \InvalidArgumentException(sprintf('Detail "%s" must be a scalar or a list of scalars.', $key));
            }
            foreach ($value as $item) {
                if (!self::isScalarOrNull($item)) {
                    throw new \InvalidArgumentException(sprintf('Detail "%s" must be a scalar or a list of scalars.', $key));
                }
            }
        }
    }

    private static function isScalarOrNull(mixed $value): bool
    {
        return $value === null || is_scalar($value);
    }
}
