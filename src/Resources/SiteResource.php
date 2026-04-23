<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Sites\SiteListDetailedItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\CreateSiteRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\DeleteSiteRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\GetSiteRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\ListSitesDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\ListSitesRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\UpdateSiteRequest;
use Simpro\PhpSdk\Simpro\Scopes\Sites\SiteScope;

/**
 * @property AbstractSimproConnector $connector
 */
final class SiteResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all sites.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListSitesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all sites with all available columns.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListSitesDetailedRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a scope for a specific site to access nested resources.
     *
     * @example
     * // Access site contacts
     * $connector->sites(companyId: 0)->site(siteId: 123)->contacts()->list();
     *
     * // Access site custom fields
     * $connector->sites(companyId: 0)->site(siteId: 123)->customFields()->list();
     */
    public function site(int|string $siteId): SiteScope
    {
        return new SiteScope($this->connector, $this->companyId, $siteId);
    }

    /**
     * Get detailed information for a specific site.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $siteId, ?array $columns = null): SiteListDetailedItem
    {
        $request = new GetSiteRequest($this->companyId, $siteId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new site.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created site
     */
    public function create(array $data): int
    {
        $request = new CreateSiteRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing site.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $siteId, array $data): Response
    {
        $request = new UpdateSiteRequest($this->companyId, $siteId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a site.
     */
    public function delete(int|string $siteId): Response
    {
        $request = new DeleteSiteRequest($this->companyId, $siteId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple sites in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/sites",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple sites in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/sites",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple sites in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/sites",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
