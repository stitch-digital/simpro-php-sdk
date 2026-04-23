<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Sites;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Sites\Contacts\SiteContact;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\Contacts\CreateSiteContactRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\Contacts\DeleteSiteContactRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\Contacts\GetSiteContactRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\Contacts\ListSiteContactsDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\Contacts\ListSiteContactsRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\Contacts\UpdateSiteContactRequest;

/**
 * Resource for managing site contacts.
 *
 * @property AbstractSimproConnector $connector
 */
final class SiteContactResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $siteId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all contacts for this site.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListSiteContactsRequest($this->companyId, $this->siteId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all contacts for this site with all available columns.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListSiteContactsDetailedRequest($this->companyId, $this->siteId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific site contact.
     *
     * @param  array<string>|null  $columns  Optional columns to retrieve
     */
    public function get(int|string $contactId, ?array $columns = null): SiteContact
    {
        $request = new GetSiteContactRequest($this->companyId, $this->siteId, $contactId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new site contact.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created contact
     */
    public function create(array $data): int
    {
        $request = new CreateSiteContactRequest($this->companyId, $this->siteId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing site contact.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $contactId, array $data): Response
    {
        $request = new UpdateSiteContactRequest($this->companyId, $this->siteId, $contactId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a site contact.
     */
    public function delete(int|string $contactId): Response
    {
        $request = new DeleteSiteContactRequest($this->companyId, $this->siteId, $contactId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple site contacts in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/sites/{$this->siteId}/contacts",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple site contacts in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/sites/{$this->siteId}/contacts",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple site contacts in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/sites/{$this->siteId}/contacts",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
