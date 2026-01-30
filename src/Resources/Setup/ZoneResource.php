<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\Zone;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Zones\CreateZoneRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Zones\DeleteZoneRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Zones\GetZoneRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Zones\ListZonesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Zones\UpdateZoneRequest;

/**
 * Resource for managing zones.
 *
 * @property AbstractSimproConnector $connector
 */
final class ZoneResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all zones.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListZonesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific zone.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $zoneId, ?array $columns = null): Zone
    {
        $request = new GetZoneRequest($this->companyId, $zoneId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new zone.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateZoneRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a zone.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $zoneId, array $data): Response
    {
        $request = new UpdateZoneRequest($this->companyId, $zoneId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a zone.
     */
    public function delete(int|string $zoneId): Response
    {
        $request = new DeleteZoneRequest($this->companyId, $zoneId);

        return $this->connector->send($request);
    }
}
