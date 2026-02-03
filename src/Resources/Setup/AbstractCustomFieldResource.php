<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomField;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListDetailedCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;

/**
 * Abstract resource for managing custom fields.
 *
 * @property AbstractSimproConnector $connector
 */
abstract class AbstractCustomFieldResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        protected readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    abstract protected function createListRequest(int $companyId): AbstractListCustomFieldsRequest;

    abstract protected function createListDetailedRequest(int $companyId): AbstractListDetailedCustomFieldsRequest;

    abstract protected function createGetRequest(int $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest;

    abstract protected function createCreateRequest(int $companyId, array $data): AbstractCreateCustomFieldRequest;

    abstract protected function createUpdateRequest(int $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest;

    abstract protected function createDeleteRequest(int $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest;

    /**
     * List all custom fields.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = $this->createListRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all custom fields with full details.
     *
     * Returns CustomField DTOs with all fields (ID, Name, Type, ListItems, IsMandatory, Order, Archived, Locked).
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = $this->createListDetailedRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific custom field.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $customFieldId, ?array $columns = null): CustomField
    {
        $request = $this->createGetRequest($this->companyId, $customFieldId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new custom field.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = $this->createCreateRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a custom field.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customFieldId, array $data): Response
    {
        $request = $this->createUpdateRequest($this->companyId, $customFieldId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a custom field.
     */
    public function delete(int|string $customFieldId): Response
    {
        $request = $this->createDeleteRequest($this->companyId, $customFieldId);

        return $this->connector->send($request);
    }
}
