<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices;

enum RecurringFrequency: string
{
    case Week = 'week';
    case Month = 'month';
    case Year = 'year';
}
