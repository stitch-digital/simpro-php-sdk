<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contracts;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for detailed service level information.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}
 * The ServiceLevels array contains this nested structure.
 */
final readonly class ServiceLevelDetail
{
    public function __construct(
        public int $id,
        public string $name,
        public Reference $prebuild,
        public float $time,
        public float $chargeRate,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? '',
            prebuild: Reference::fromArray($data['Prebuild']),
            time: isset($data['Time']) ? (float) $data['Time'] : 0.0,
            chargeRate: isset($data['ChargeRate']) ? (float) $data['ChargeRate'] : 0.0,
        );
    }
}
