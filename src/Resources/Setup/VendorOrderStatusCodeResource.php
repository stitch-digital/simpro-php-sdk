<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\VendorOrderStatusCode;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\VendorOrders\CreateVendorOrderStatusCodeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\VendorOrders\DeleteVendorOrderStatusCodeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\VendorOrders\GetVendorOrderStatusCodeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\VendorOrders\ListVendorOrderStatusCodesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\VendorOrders\UpdateVendorOrderStatusCodeRequest;

/**
 * Resource for managing VendorOrderStatusCodes.
 *
 * @property AbstractSimproConnector $connector
 */
final class VendorOrderStatusCodeResource extends BaseResource
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
        $request = new ListVendorOrderStatusCodesRequest($this->companyId);

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
    public function get(int|string $statusCodeId, ?array $columns = null): VendorOrderStatusCode
    {
        $request = new GetVendorOrderStatusCodeRequest($this->companyId, $statusCodeId);

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
        $request = new CreateVendorOrderStatusCodeRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $statusCodeId, array $data): Response
    {
        $request = new UpdateVendorOrderStatusCodeRequest($this->companyId, $statusCodeId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $statusCodeId): Response
    {
        $request = new DeleteVendorOrderStatusCodeRequest($this->companyId, $statusCodeId);

        return $this->connector->send($request);
    }
}
