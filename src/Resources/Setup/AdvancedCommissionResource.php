<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\AdvancedCommission;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Advanced\CreateAdvancedCommissionRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Advanced\DeleteAdvancedCommissionRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Advanced\GetAdvancedCommissionRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Advanced\ListAdvancedCommissionsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Advanced\ListDetailedAdvancedCommissionsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Advanced\UpdateAdvancedCommissionRequest;

/**
 * Resource for managing AdvancedCommissions.
 *
 * @property AbstractSimproConnector $connector
 */
final class AdvancedCommissionResource extends BaseResource
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
        $request = new ListAdvancedCommissionsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all advanced commissions with full details.
     *
     * Returns AdvancedCommission DTOs with all fields (ID, Name, Type, DisplayOrder, Components, Trigger).
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedAdvancedCommissionsRequest($this->companyId);

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
    public function get(int|string $commissionId, ?array $columns = null): AdvancedCommission
    {
        $request = new GetAdvancedCommissionRequest($this->companyId, $commissionId);

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
        $request = new CreateAdvancedCommissionRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $commissionId, array $data): Response
    {
        $request = new UpdateAdvancedCommissionRequest($this->companyId, $commissionId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $commissionId): Response
    {
        $request = new DeleteAdvancedCommissionRequest($this->companyId, $commissionId);

        return $this->connector->send($request);
    }
}
