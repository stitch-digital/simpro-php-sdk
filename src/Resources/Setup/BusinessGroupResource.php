<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\BusinessGroup;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\BusinessGroups\CreateBusinessGroupRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\BusinessGroups\DeleteBusinessGroupRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\BusinessGroups\GetBusinessGroupRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\BusinessGroups\ListBusinessGroupsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\BusinessGroups\UpdateBusinessGroupRequest;

/**
 * Resource for managing business groups.
 *
 * @property AbstractSimproConnector $connector
 */
final class BusinessGroupResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all business groups.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListBusinessGroupsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific business group.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $groupId, ?array $columns = null): BusinessGroup
    {
        $request = new GetBusinessGroupRequest($this->companyId, $groupId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new business group.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateBusinessGroupRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a business group.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $groupId, array $data): Response
    {
        $request = new UpdateBusinessGroupRequest($this->companyId, $groupId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a business group.
     */
    public function delete(int|string $groupId): Response
    {
        $request = new DeleteBusinessGroupRequest($this->companyId, $groupId);

        return $this->connector->send($request);
    }
}
