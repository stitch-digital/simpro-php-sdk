<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Reports;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * Job Cost To Complete - Operations View report item.
 */
final readonly class JobCostToCompleteOperations
{
    public function __construct(
        public ?Reference $job,
        public ?Reference $customer,
        public ?Reference $site,
        public string $requestNumber,
        public BudgetBreakdown $originalEstimatedBudget,
        public BudgetBreakdown $revisedEstimatedBudget,
        public BudgetBreakdown $revizedEstimatedBudget,
        public BudgetBreakdown $currentBudget,
        public BudgetBreakdown $actualToDate,
        public BudgetBreakdown $forecastRemaining,
        public BudgetBreakdown $variance,
        public BudgetBreakdown $percentage,
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
            job: isset($data['Job']['ID']) ? Reference::fromId((int) $data['Job']['ID']) : null,
            customer: isset($data['Customer']['ID']) ? Reference::fromId((int) $data['Customer']['ID']) : null,
            site: isset($data['Site']['ID']) ? Reference::fromId((int) $data['Site']['ID']) : null,
            requestNumber: $data['RequestNumber'] ?? '',
            originalEstimatedBudget: BudgetBreakdown::fromArray($data['OriginalEstimatedBudget'] ?? []),
            revisedEstimatedBudget: BudgetBreakdown::fromArray($data['RevisedEstimatedBudget'] ?? []),
            revizedEstimatedBudget: BudgetBreakdown::fromArray($data['RevizedEstimatedBudget'] ?? []),
            currentBudget: BudgetBreakdown::fromArray($data['CurrentBudget'] ?? []),
            actualToDate: BudgetBreakdown::fromArray($data['ActualToDate'] ?? []),
            forecastRemaining: BudgetBreakdown::fromArray($data['ForecastRemaining'] ?? []),
            variance: BudgetBreakdown::fromArray($data['Variance'] ?? []),
            percentage: BudgetBreakdown::fromArray($data['Percentage'] ?? []),
        );
    }
}
