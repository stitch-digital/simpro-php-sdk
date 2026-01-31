<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\Materials;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\StockTakeReason;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\StockTakeReasons\CreateStockTakeReasonRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\StockTakeReasons\DeleteStockTakeReasonRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\StockTakeReasons\GetStockTakeReasonRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\StockTakeReasons\ListStockTakeReasonsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\StockTakeReasons\UpdateStockTakeReasonRequest;

/**
 * Resource for managing StockTakeReasons.
 *
 * @property AbstractSimproConnector $connector
 */
final class StockTakeReasonResource extends BaseResource
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
        $request = new ListStockTakeReasonsRequest($this->companyId);

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
    public function get(int|string $optionId, ?array $columns = null): StockTakeReason
    {
        $request = new GetStockTakeReasonRequest($this->companyId, $optionId);

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
        $request = new CreateStockTakeReasonRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $optionId, array $data): Response
    {
        $request = new UpdateStockTakeReasonRequest($this->companyId, $optionId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $optionId): Response
    {
        $request = new DeleteStockTakeReasonRequest($this->companyId, $optionId);

        return $this->connector->send($request);
    }
}
