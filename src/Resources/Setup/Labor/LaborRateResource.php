<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\Labor;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\LaborRate;
use Simpro\PhpSdk\Simpro\Data\Setup\Overhead;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\LaborRates\CreateLaborRateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\LaborRates\DeleteLaborRateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\LaborRates\GetLaborRateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\LaborRates\GetOverheadRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\LaborRates\ListDetailedLaborRatesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\LaborRates\ListLaborRatesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\LaborRates\UpdateLaborRateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\LaborRates\UpdateOverheadRequest;

/**
 * Resource for managing LaborRates.
 *
 * @property AbstractSimproConnector $connector
 */
final class LaborRateResource extends BaseResource
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
        $request = new ListLaborRatesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all labor rates with full details.
     *
     * Returns LaborRate DTOs with all fields including TaxCode and Plant references.
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedLaborRatesRequest($this->companyId);

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
    public function get(int|string $laborRateId, ?array $columns = null): LaborRate
    {
        $request = new GetLaborRateRequest($this->companyId, $laborRateId);

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
        $request = new CreateLaborRateRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $laborRateId, array $data): Response
    {
        $request = new UpdateLaborRateRequest($this->companyId, $laborRateId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $laborRateId): Response
    {
        $request = new DeleteLaborRateRequest($this->companyId, $laborRateId);

        return $this->connector->send($request);
    }

    /**
     * Get overhead settings.
     */
    public function getOverhead(): Overhead
    {
        $request = new GetOverheadRequest($this->companyId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update overhead settings.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateOverhead(array $data): Response
    {
        $request = new UpdateOverheadRequest($this->companyId, $data);

        return $this->connector->send($request);
    }
}
