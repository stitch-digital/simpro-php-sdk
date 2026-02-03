<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\Materials;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\PurchasingStage;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PurchasingStages\CreatePurchasingStageRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PurchasingStages\DeletePurchasingStageRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PurchasingStages\GetPurchasingStageRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PurchasingStages\ListDetailedPurchasingStagesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PurchasingStages\ListPurchasingStagesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PurchasingStages\UpdatePurchasingStageRequest;

/**
 * Resource for managing PurchasingStages.
 *
 * @property AbstractSimproConnector $connector
 */
final class PurchasingStageResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListPurchasingStagesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all purchasing stages with full details.
     *
     * Returns PurchasingStage DTOs with all fields including Archived.
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedPurchasingStagesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific item.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $purchasingStageId, ?array $columns = null): PurchasingStage
    {
        $request = new GetPurchasingStageRequest($this->companyId, $purchasingStageId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new item.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreatePurchasingStageRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $purchasingStageId, array $data): Response
    {
        $request = new UpdatePurchasingStageRequest($this->companyId, $purchasingStageId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $purchasingStageId): Response
    {
        $request = new DeletePurchasingStageRequest($this->companyId, $purchasingStageId);

        return $this->connector->send($request);
    }
}
