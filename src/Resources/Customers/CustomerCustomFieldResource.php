<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Customers;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Customers\CustomFields\GetCustomerCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\CustomFields\ListCustomerCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\CustomFields\UpdateCustomerCustomFieldRequest;

/**
 * Resource for managing customer custom fields.
 *
 * @property AbstractSimproConnector $connector
 */
final class CustomerCustomFieldResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $customerId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all custom fields for this customer.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCustomerCustomFieldsRequest($this->companyId, $this->customerId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific custom field.
     */
    public function get(int|string $customFieldId): CustomField
    {
        $request = new GetCustomerCustomFieldRequest($this->companyId, $this->customerId, $customFieldId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a custom field value.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customFieldId, array $data): Response
    {
        $request = new UpdateCustomerCustomFieldRequest($this->companyId, $this->customerId, $customFieldId, $data);

        return $this->connector->send($request);
    }
}
