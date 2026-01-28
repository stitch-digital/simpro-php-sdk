<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Lock\CreateCostCenterLockRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Lock\DeleteCostCenterLockRequest;

/**
 * Resource for managing cost center locks.
 *
 * @property AbstractSimproConnector $connector
 */
final class CostCenterLockResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector);
    }

    /**
     * Lock the cost center.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data = []): Response
    {
        $request = new CreateCostCenterLockRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request);
    }

    /**
     * Unlock the cost center.
     */
    public function delete(): Response
    {
        $request = new DeleteCostCenterLockRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);

        return $this->connector->send($request);
    }
}
