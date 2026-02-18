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
        public ?BudgetBreakdown $originalEstimatedBudget,
        public ?BudgetBreakdown $revisedEstimatedBudget,
        public ?BudgetBreakdown $revizedEstimatedBudget,
        public ?BudgetBreakdown $currentBudget,
        public ?BudgetBreakdown $actualToDate,
        public ?BudgetBreakdown $forecastRemaining,
        public ?BudgetBreakdown $variance,
        public ?BudgetBreakdown $percentage,
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
            originalEstimatedBudget: ! empty($data['OriginalEstimatedBudget']) ? BudgetBreakdown::fromArray($data['OriginalEstimatedBudget']) : null,
            revisedEstimatedBudget: ! empty($data['RevisedEstimatedBudget']) ? BudgetBreakdown::fromArray($data['RevisedEstimatedBudget']) : null,
            revizedEstimatedBudget: ! empty($data['RevizedEstimatedBudget']) ? BudgetBreakdown::fromArray($data['RevizedEstimatedBudget']) : null,
            currentBudget: ! empty($data['CurrentBudget']) ? BudgetBreakdown::fromArray($data['CurrentBudget']) : null,
            actualToDate: ! empty($data['ActualToDate']) ? BudgetBreakdown::fromArray($data['ActualToDate']) : null,
            forecastRemaining: ! empty($data['ForecastRemaining']) ? BudgetBreakdown::fromArray($data['ForecastRemaining']) : null,
            variance: ! empty($data['Variance']) ? BudgetBreakdown::fromArray($data['Variance']) : null,
            percentage: ! empty($data['Percentage']) ? BudgetBreakdown::fromArray($data['Percentage']) : null,
        );
    }
}
