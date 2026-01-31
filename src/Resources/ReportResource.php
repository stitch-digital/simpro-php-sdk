<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Reports\JobCostToCompleteFinancial;
use Simpro\PhpSdk\Simpro\Data\Reports\JobCostToCompleteOperations;
use Simpro\PhpSdk\Simpro\Requests\Reports\GetJobCostToCompleteFinancialRequest;
use Simpro\PhpSdk\Simpro\Requests\Reports\GetJobCostToCompleteOperationsRequest;

/**
 * Resource for accessing Simpro reports.
 *
 * @property AbstractSimproConnector $connector
 */
final class ReportResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * Get job cost to complete - financial view report.
     *
     * @param  array<string, mixed>  $filters  Optional filters to apply
     * @return array<JobCostToCompleteFinancial>
     */
    public function jobCostToCompleteFinancial(array $filters = []): array
    {
        $request = new GetJobCostToCompleteFinancialRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Get job cost to complete - operations view report.
     *
     * @param  array<string, mixed>  $filters  Optional filters to apply
     * @return array<JobCostToCompleteOperations>
     */
    public function jobCostToCompleteOperations(array $filters = []): array
    {
        $request = new GetJobCostToCompleteOperationsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return $this->connector->send($request)->dto();
    }
}
