<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Query;

/**
 * Sort direction for ordering query results.
 */
enum SortDirection: string
{
    case Ascending = 'asc';
    case Descending = 'desc';
}
