<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\OneOffs;

/**
 * Simpro spells the labour one-off "Labour" on recurring invoices (and "Labor" on jobs).
 */
enum OneOffType: string
{
    case Material = 'Material';
    case Labour = 'Labour';
}
