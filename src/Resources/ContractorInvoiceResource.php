<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\ContractorInvoices\ContractorInvoice;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\CreateContractorInvoiceRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\DeleteContractorInvoiceRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\GetContractorInvoiceRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\ListDetailedContractorInvoicesRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\ListContractorInvoicesRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\UpdateContractorInvoiceRequest;
use Simpro\PhpSdk\Simpro\Scopes\ContractorInvoices\ContractorInvoiceScope;

/**
 * @property AbstractSimproConnector $connector
 */
final class ContractorInvoiceResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all contractor invoices.
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorInvoicesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all contractor invoices with full details.
     *
     * Uses the columns parameter to return full ContractorInvoice DTOs.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedContractorInvoicesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific contractor invoice.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $contractorInvoiceId, ?array $columns = null): ContractorInvoice
    {
        $request = new GetContractorInvoiceRequest($this->companyId, $contractorInvoiceId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new contractor invoice.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created contractor invoice
     */
    public function create(array $data): int
    {
        $request = new CreateContractorInvoiceRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing contractor invoice.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $contractorInvoiceId, array $data): Response
    {
        $request = new UpdateContractorInvoiceRequest($this->companyId, $contractorInvoiceId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a contractor invoice.
     */
    public function delete(int|string $contractorInvoiceId): Response
    {
        $request = new DeleteContractorInvoiceRequest($this->companyId, $contractorInvoiceId);

        return $this->connector->send($request);
    }

    /**
     * Navigate to a specific contractor invoice scope for accessing sub-resources.
     */
    public function contractorInvoice(int|string $contractorInvoiceId): ContractorInvoiceScope
    {
        return new ContractorInvoiceScope($this->connector, $this->companyId, $contractorInvoiceId);
    }
}
