<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices;

enum RecurringInvoiceType: string
{
    case Project = 'Project';
    case Service = 'Service';
    case Prepaid = 'Prepaid';
}
