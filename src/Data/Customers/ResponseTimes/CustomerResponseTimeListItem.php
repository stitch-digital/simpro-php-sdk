<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\ResponseTimes;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for customer response time list item.
 *
 * Based on: GET /api/v1.0/companies/{companyID}/customers/{customerID}/responseTimes/
 */
final readonly class CustomerResponseTimeListItem
{
    public function __construct(
        public ?Reference $responseTime,
        public int $days,
        public int $hours,
        public int $minutes,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            responseTime: ! empty($data['ResponseTime']) ? Reference::fromArray($data['ResponseTime']) : null,
            days: (int) ($data['Days'] ?? 0),
            hours: (int) ($data['Hours'] ?? 0),
            minutes: (int) ($data['Minutes'] ?? 0),
        );
    }
}
