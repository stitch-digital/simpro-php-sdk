<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomerInvoiceStatusCode;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\CustomerInvoices\CreateCustomerInvoiceStatusCodeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\CustomerInvoices\DeleteCustomerInvoiceStatusCodeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\CustomerInvoices\GetCustomerInvoiceStatusCodeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\CustomerInvoices\ListCustomerInvoiceStatusCodesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\CustomerInvoices\ListDetailedCustomerInvoiceStatusCodesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\CustomerInvoices\UpdateCustomerInvoiceStatusCodeRequest;

/**
 * Resource for managing CustomerInvoiceStatusCodes.
 *
 * @property AbstractSimproConnector $connector
 */
final class CustomerInvoiceStatusCodeResource extends BaseResource
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
        $request = new ListCustomerInvoiceStatusCodesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all customer invoice status codes with full details.
     *
     * Returns CustomerInvoiceStatusCode DTOs with all fields (ID, Name, Color, Priority).
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedCustomerInvoiceStatusCodesRequest($this->companyId);

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
    public function get(int|string $statusCodeId, ?array $columns = null): CustomerInvoiceStatusCode
    {
        $request = new GetCustomerInvoiceStatusCodeRequest($this->companyId, $statusCodeId);

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
        $request = new CreateCustomerInvoiceStatusCodeRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $statusCodeId, array $data): Response
    {
        $request = new UpdateCustomerInvoiceStatusCodeRequest($this->companyId, $statusCodeId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $statusCodeId): Response
    {
        $request = new DeleteCustomerInvoiceStatusCodeRequest($this->companyId, $statusCodeId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple customer invoice status codes in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/customerInvoices",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple customer invoice status codes in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/customerInvoices",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple customer invoice status codes in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/customerInvoices",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
