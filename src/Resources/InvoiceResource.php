<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Invoices\Invoice;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CreateInvoiceRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\DeleteInvoiceRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\GetInvoiceRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\ListInvoicesRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\UpdateInvoiceRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class InvoiceResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all invoices.
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListInvoicesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific invoice.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $invoiceId, ?array $columns = null): Invoice
    {
        $request = new GetInvoiceRequest($this->companyId, $invoiceId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new invoice.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created invoice
     */
    public function create(array $data): int
    {
        $request = new CreateInvoiceRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing invoice.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $invoiceId, array $data): Response
    {
        $request = new UpdateInvoiceRequest($this->companyId, $invoiceId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an invoice.
     */
    public function delete(int|string $invoiceId): Response
    {
        $request = new DeleteInvoiceRequest($this->companyId, $invoiceId);

        return $this->connector->send($request);
    }
}
