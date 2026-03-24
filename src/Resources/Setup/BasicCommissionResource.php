<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\BasicCommission;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Basic\CreateBasicCommissionRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Basic\DeleteBasicCommissionRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Basic\GetBasicCommissionRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Basic\ListBasicCommissionsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Basic\ListDetailedBasicCommissionsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Basic\UpdateBasicCommissionRequest;

/**
 * Resource for managing BasicCommissions.
 *
 * @property AbstractSimproConnector $connector
 */
final class BasicCommissionResource extends BaseResource
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
        $request = new ListBasicCommissionsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all basic commissions with full details.
     *
     * Returns BasicCommission DTOs with all fields (ID, Name, Type, DisplayOrder, Rule, Rate, Trigger).
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedBasicCommissionsRequest($this->companyId);

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
    public function get(int|string $commissionId, ?array $columns = null): BasicCommission
    {
        $request = new GetBasicCommissionRequest($this->companyId, $commissionId);

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
        $request = new CreateBasicCommissionRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $commissionId, array $data): Response
    {
        $request = new UpdateBasicCommissionRequest($this->companyId, $commissionId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $commissionId): Response
    {
        $request = new DeleteBasicCommissionRequest($this->companyId, $commissionId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple basic commissions in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/commissions/basic",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple basic commissions in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/commissions/basic",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple basic commissions in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/commissions/basic",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
