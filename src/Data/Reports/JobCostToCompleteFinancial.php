<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Reports;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * Job Cost To Complete - Financial View report item.
 */
final readonly class JobCostToCompleteFinancial
{
    public function __construct(
        public ?Reference $job,
        public ?Reference $customer,
        public ?Reference $site,
        public string $requestNumber,
        public float $total,
        public float $claimedToDate,
        public float $billedPercentage,
        public float $costToDate,
        public float $costToComplete,
        public float $percentageComplete,
        public float $netMarginToDate,
        public float $projectedNetMargin,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            job: ! empty($data['Job']['ID']) ? Reference::fromId((int) $data['Job']['ID']) : null,
            customer: ! empty($data['Customer']['ID']) ? Reference::fromId((int) $data['Customer']['ID']) : null,
            site: ! empty($data['Site']['ID']) ? Reference::fromId((int) $data['Site']['ID']) : null,
            requestNumber: $data['RequestNumber'] ?? '',
            total: (float) ($data['Total'] ?? 0),
            claimedToDate: (float) ($data['ClaimedToDate'] ?? 0),
            billedPercentage: (float) ($data['BilledPercentage'] ?? 0),
            costToDate: (float) ($data['CostToDate'] ?? 0),
            costToComplete: (float) ($data['CostToComplete'] ?? 0),
            percentageComplete: (float) ($data['PercentageComplete'] ?? 0),
            netMarginToDate: (float) ($data['NetMarginToDate'] ?? 0),
            projectedNetMargin: (float) ($data['ProjectedNetMargin'] ?? 0),
        );
    }
}
