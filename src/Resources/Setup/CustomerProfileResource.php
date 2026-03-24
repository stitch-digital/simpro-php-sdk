<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomerProfile;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerProfiles\CreateCustomerProfileRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerProfiles\DeleteCustomerProfileRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerProfiles\GetCustomerProfileRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerProfiles\ListCustomerProfilesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerProfiles\ListDetailedCustomerProfilesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerProfiles\UpdateCustomerProfileRequest;

/**
 * Resource for managing CustomerProfiles.
 *
 * @property AbstractSimproConnector $connector
 */
final class CustomerProfileResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all customer profiles with minimal fields (ID, Name).
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCustomerProfilesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all customer profiles with full details.
     *
     * Returns CustomerProfile DTOs with all fields (ID, Name, Archived).
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedCustomerProfilesRequest($this->companyId);

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
    public function get(int|string $customerProfileId, ?array $columns = null): CustomerProfile
    {
        $request = new GetCustomerProfileRequest($this->companyId, $customerProfileId);

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
        $request = new CreateCustomerProfileRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customerProfileId, array $data): Response
    {
        $request = new UpdateCustomerProfileRequest($this->companyId, $customerProfileId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $customerProfileId): Response
    {
        $request = new DeleteCustomerProfileRequest($this->companyId, $customerProfileId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple customer profiles in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/customerProfiles",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple customer profiles in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/customerProfiles",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple customer profiles in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/customerProfiles",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
