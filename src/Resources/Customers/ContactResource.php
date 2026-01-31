<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Customers;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Customers\Contacts\Contact;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\CreateContactRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\DeleteContactRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\GetContactRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\ListContactsDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\ListContactsRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\UpdateContactRequest;

/**
 * Resource for managing customer contacts.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContactResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $customerId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all contacts for this customer.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContactsRequest($this->companyId, $this->customerId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all contacts for this customer with all available columns.
     *
     * Returns detailed Contact DTOs with full field data.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListContactsDetailedRequest($this->companyId, $this->customerId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific contact.
     *
     * @param  array<string>|null  $columns  Optional columns to retrieve
     */
    public function get(int|string $contactId, ?array $columns = null): Contact
    {
        $request = new GetContactRequest($this->companyId, $this->customerId, $contactId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new contact.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created contact
     */
    public function create(array $data): int
    {
        $request = new CreateContactRequest($this->companyId, $this->customerId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing contact.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $contactId, array $data): Response
    {
        $request = new UpdateContactRequest($this->companyId, $this->customerId, $contactId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a contact.
     */
    public function delete(int|string $contactId): Response
    {
        $request = new DeleteContactRequest($this->companyId, $this->customerId, $contactId);

        return $this->connector->send($request);
    }
}
