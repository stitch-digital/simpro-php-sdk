<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\Materials;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\Uom;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\Uoms\CreateUomRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\Uoms\DeleteUomRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\Uoms\GetUomRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\Uoms\ListDetailedUomsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\Uoms\ListUomsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\Uoms\UpdateUomRequest;

/**
 * Resource for managing Uoms.
 *
 * @property AbstractSimproConnector $connector
 */
final class UomResource extends BaseResource
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
        $request = new ListUomsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all units of measurement with full details.
     *
     * Returns Uom DTOs with all fields including WholeNoOnly.
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedUomsRequest($this->companyId);

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
    public function get(int|string $uomId, ?array $columns = null): Uom
    {
        $request = new GetUomRequest($this->companyId, $uomId);

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
        $request = new CreateUomRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $uomId, array $data): Response
    {
        $request = new UpdateUomRequest($this->companyId, $uomId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $uomId): Response
    {
        $request = new DeleteUomRequest($this->companyId, $uomId);

        return $this->connector->send($request);
    }
}
