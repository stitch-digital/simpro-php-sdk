<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices;

enum InvoiceDatePeriod: string
{
    case Advance = 'Advance';
    case Arrears = 'Arrears';
}
