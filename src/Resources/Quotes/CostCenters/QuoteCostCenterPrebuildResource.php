<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Prebuilds\PrebuildItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Prebuilds\CreateQuoteCostCenterPrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Prebuilds\DeleteQuoteCostCenterPrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Prebuilds\GetQuoteCostCenterPrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Prebuilds\ListQuoteCostCenterPrebuildsRequest;

/**
 * Resource for managing prebuilds within a quote cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class QuoteCostCenterPrebuildResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $quoteId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all prebuild items for this quote cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListQuoteCostCenterPrebuildsRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific prebuild item.
     */
    public function get(int|string $prebuildId): PrebuildItem
    {
        $request = new GetQuoteCostCenterPrebuildRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $prebuildId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new prebuild item.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created prebuild item
     */
    public function create(array $data): int
    {
        $request = new CreateQuoteCostCenterPrebuildRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete a prebuild item.
     */
    public function delete(int|string $prebuildId): Response
    {
        $request = new DeleteQuoteCostCenterPrebuildRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $prebuildId);

        return $this->connector->send($request);
    }
}
