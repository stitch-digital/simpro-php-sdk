<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Query;

/**
 * Search logic determines how multiple search criteria are combined.
 */
enum SearchLogic: string
{
    /**
     * All criteria must match (AND).
     */
    case All = 'all';

    /**
     * Any criterion can match (OR).
     */
    case Any = 'any';
}
