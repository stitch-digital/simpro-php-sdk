<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Customers;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Customers\Notes\CustomerNote;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Customers\Notes\CreateCustomerNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Notes\DeleteCustomerNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Notes\GetCustomerNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Notes\ListCustomerNotesRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Notes\UpdateCustomerNoteRequest;

/**
 * Resource for managing customer notes.
 *
 * @property AbstractSimproConnector $connector
 */
final class CustomerNoteResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $customerId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all notes for this customer.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCustomerNotesRequest($this->companyId, $this->customerId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific note.
     *
     * @param  array<string>|null  $columns  Optional columns to retrieve
     */
    public function get(int|string $noteId, ?array $columns = null): CustomerNote
    {
        $request = new GetCustomerNoteRequest($this->companyId, $this->customerId, $noteId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new note.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created note
     */
    public function create(array $data): int
    {
        $request = new CreateCustomerNoteRequest($this->companyId, $this->customerId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing note.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $noteId, array $data): Response
    {
        $request = new UpdateCustomerNoteRequest($this->companyId, $this->customerId, $noteId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a note.
     */
    public function delete(int|string $noteId): Response
    {
        $request = new DeleteCustomerNoteRequest($this->companyId, $this->customerId, $noteId);

        return $this->connector->send($request);
    }
}
