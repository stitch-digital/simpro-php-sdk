<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomerProfile;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerProfiles\CreateCustomerProfileRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerProfiles\DeleteCustomerProfileRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerProfiles\GetCustomerProfileRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerProfiles\ListCustomerProfilesRequest;
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
     * List all.
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
}
