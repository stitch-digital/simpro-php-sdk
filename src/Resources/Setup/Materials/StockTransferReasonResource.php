<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\Materials;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\StockTransferReason;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\StockTransferReasons\CreateStockTransferReasonRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\StockTransferReasons\DeleteStockTransferReasonRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\StockTransferReasons\GetStockTransferReasonRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\StockTransferReasons\ListStockTransferReasonsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\StockTransferReasons\UpdateStockTransferReasonRequest;

/**
 * Resource for managing StockTransferReasons.
 *
 * @property AbstractSimproConnector $connector
 */
final class StockTransferReasonResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
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
        $request = new ListStockTransferReasonsRequest($this->companyId);

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
    public function get(int|string $optionId, ?array $columns = null): StockTransferReason
    {
        $request = new GetStockTransferReasonRequest($this->companyId, $optionId);

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
        $request = new CreateStockTransferReasonRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $optionId, array $data): Response
    {
        $request = new UpdateStockTransferReasonRequest($this->companyId, $optionId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $optionId): Response
    {
        $request = new DeleteStockTransferReasonRequest($this->companyId, $optionId);

        return $this->connector->send($request);
    }
}
