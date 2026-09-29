<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Activity;

/**
 * On a command: leave it out of the activity journal — for purely technical
 * commands nobody will ever ask about. Commands are journaled otherwise.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class NotJournaled
{
}
