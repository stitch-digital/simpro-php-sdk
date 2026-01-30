<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Setup;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Setup\Labor\FitTimeResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\Labor\LaborRateResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\Labor\PlantRateResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\Labor\ScheduleRateResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\Labor\ServiceFeeResource;

/**
 * Scope for navigating labor-related setup resources.
 */
final class LaborScope
{
    public function __construct(
        private readonly AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {}

    /**
     * Access fit time endpoints.
     */
    public function fitTimes(): FitTimeResource
    {
        return new FitTimeResource($this->connector, $this->companyId);
    }

    /**
     * Access labor rate endpoints.
     */
    public function laborRates(): LaborRateResource
    {
        return new LaborRateResource($this->connector, $this->companyId);
    }

    /**
     * Access plant rate endpoints.
     */
    public function plantRates(): PlantRateResource
    {
        return new PlantRateResource($this->connector, $this->companyId);
    }

    /**
     * Access schedule rate endpoints.
     */
    public function scheduleRates(): ScheduleRateResource
    {
        return new ScheduleRateResource($this->connector, $this->companyId);
    }

    /**
     * Access service fee endpoints.
     */
    public function serviceFees(): ServiceFeeResource
    {
        return new ServiceFeeResource($this->connector, $this->companyId);
    }
}
