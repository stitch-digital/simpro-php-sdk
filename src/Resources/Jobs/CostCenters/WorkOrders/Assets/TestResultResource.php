<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\WorkOrders\Assets;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\Assets\TestResults\ListTestResultsRequest;

/**
 * Resource for accessing work order asset test results.
 *
 * @property AbstractSimproConnector $connector
 */
final class TestResultResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $workOrderId,
        private readonly int|string $assetId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all test results for this work order asset.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListTestResultsRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId,
            $this->assetId
        );

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }
}
