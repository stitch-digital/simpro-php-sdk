<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Companies;

enum ScheduleFormat: int
{
    case FIFTEEN_MINUTES = 15;
    case THIRTY_MINUTES = 30;
}
