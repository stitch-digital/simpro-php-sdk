<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Jobs;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenterResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific job section, providing access to nested resources.
 *
 * @example
 * // Access cost centers for this section
 * $connector->jobs(companyId: 0)->job(jobId: 123)->section(sectionId: 1)->costCenters()->list();
 *
 * // Navigate to a specific cost center
 * $connector->jobs(companyId: 0)->job(jobId: 123)->section(sectionId: 1)->costCenter(costCenterId: 5)->labor()->list();
 */
final class SectionScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access cost centers for this section.
     */
    public function costCenters(): CostCenterResource
    {
        return new CostCenterResource($this->connector, $this->companyId, $this->jobId, $this->sectionId);
    }

    /**
     * Navigate to a specific cost center scope.
     */
    public function costCenter(int|string $costCenterId): CostCenterScope
    {
        return new CostCenterScope($this->connector, $this->companyId, $this->jobId, $this->sectionId, $costCenterId);
    }
}
