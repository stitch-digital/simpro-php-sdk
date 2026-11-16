<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\RecurringInvoice;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CreateRecurringInvoiceRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\DeleteRecurringInvoiceRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\GetRecurringInvoiceRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\ListRecurringInvoicesDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\ListRecurringInvoicesRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\UpdateRecurringInvoiceRequest;
use Simpro\PhpSdk\Simpro\Scopes\RecurringInvoices\RecurringInvoiceScope;

/**
 * Recurring invoice templates: the header record Simpro raises invoices from on a schedule.
 *
 * @property AbstractSimproConnector $connector
 */
final class RecurringInvoiceResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List recurring invoices as lightweight list items (ID, Description, Total).
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     *
     * @example
     * $templates = $connector->recurringInvoices(companyId: 0)->list()->all();
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListRecurringInvoicesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List recurring invoices with every column, hydrated into full RecurringInvoice DTOs.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     *
     * @example
     * $templates = $connector->recurringInvoices(companyId: 0)->listDetailed()
     *     ->search(Search::make()->column('Customer.ID')->equals(2048))
     *     ->all();
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListRecurringInvoicesDetailedRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Navigate to a specific recurring invoice's nested resources.
     *
     * @example
     * $connector->recurringInvoices(companyId: 0)->recurringInvoice(501)->sections()->list();
     */
    public function recurringInvoice(int|string $recurringInvoiceId): RecurringInvoiceScope
    {
        return new RecurringInvoiceScope($this->connector, $this->companyId, $recurringInvoiceId);
    }

    /**
     * Get a specific recurring invoice.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $recurringInvoiceId, ?array $columns = null): RecurringInvoice
    {
        $request = new GetRecurringInvoiceRequest($this->companyId, $recurringInvoiceId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a recurring invoice. `Type` and `Site` are required by Simpro.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created recurring invoice
     */
    public function create(array $data): int
    {
        $request = new CreateRecurringInvoiceRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a recurring invoice.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $recurringInvoiceId, array $data): Response
    {
        $request = new UpdateRecurringInvoiceRequest($this->companyId, $recurringInvoiceId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a recurring invoice.
     */
    public function delete(int|string $recurringInvoiceId): Response
    {
        $request = new DeleteRecurringInvoiceRequest($this->companyId, $recurringInvoiceId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple recurring invoices in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest("/api/v1.0/companies/{$this->companyId}/recurringInvoices", $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple recurring invoices in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest("/api/v1.0/companies/{$this->companyId}/recurringInvoices", $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple recurring invoices in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest("/api/v1.0/companies/{$this->companyId}/recurringInvoices", $ids);

        return $this->connector->send($request)->dto();
    }
}
