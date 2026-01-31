<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Customers;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\CustomFields\GetContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\CustomFields\ListContactCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\CustomFields\UpdateContactCustomFieldRequest;

/**
 * Resource for managing contact custom fields.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContactCustomFieldResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contactId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all custom fields for this contact.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContactCustomFieldsRequest($this->companyId, $this->customerId, $this->contactId);

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
        $request = new GetContactCustomFieldRequest($this->companyId, $this->customerId, $this->contactId, $customFieldId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a custom field value.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customFieldId, array $data): Response
    {
        $request = new UpdateContactCustomFieldRequest($this->companyId, $this->customerId, $this->contactId, $customFieldId, $data);

        return $this->connector->send($request);
    }
}
