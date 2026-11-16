<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\RecurringInvoices;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\Sections\Section;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\Sections\CreateSectionRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\Sections\DeleteSectionRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\Sections\GetSectionRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\Sections\ListSectionsRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\Sections\UpdateSectionRequest;

/**
 * Resource for managing sections within a recurring invoice.
 *
 * @property AbstractSimproConnector $connector
 */
final class RecurringInvoiceSectionResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $recurringInvoiceId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all sections for this recurring invoice.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListSectionsRequest($this->companyId, $this->recurringInvoiceId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific section.
     */
    public function get(int|string $sectionId): Section
    {
        $request = new GetSectionRequest($this->companyId, $this->recurringInvoiceId, $sectionId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new section.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created section
     */
    public function create(array $data): int
    {
        $request = new CreateSectionRequest($this->companyId, $this->recurringInvoiceId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing section.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $sectionId, array $data): Response
    {
        $request = new UpdateSectionRequest($this->companyId, $this->recurringInvoiceId, $sectionId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a section.
     */
    public function delete(int|string $sectionId): Response
    {
        $request = new DeleteSectionRequest($this->companyId, $this->recurringInvoiceId, $sectionId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple sections in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple sections in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple sections in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
