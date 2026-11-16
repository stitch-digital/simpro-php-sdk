<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Common;

enum BillableStatus: string
{
    case Billable = 'Billable';
    case NonBillable = 'NonBillable';
    case NonBillableIncludeCosts = 'NonBillableIncludeCosts';
}
