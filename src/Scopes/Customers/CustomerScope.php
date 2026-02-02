<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Customers;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Customers\ContactResource;
use Simpro\PhpSdk\Simpro\Resources\Customers\ContractResource;
use Simpro\PhpSdk\Simpro\Resources\Customers\CustomerCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Customers\CustomerLaborRateResource;
use Simpro\PhpSdk\Simpro\Resources\Customers\CustomerNoteResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific customer, providing access to nested resources.
 *
 * @example
 * // Access customer contacts
 * $connector->customers(companyId: 0)->customer(customerId: 123)->contacts()->list();
 *
 * // Navigate to a specific contact
 * $connector->customers(companyId: 0)->customer(customerId: 123)->contact(contactId: 1)->customFields()->list();
 *
 * // Access customer contracts
 * $connector->customers(companyId: 0)->customer(customerId: 123)->contracts()->list();
 *
 * // Access customer custom fields
 * $connector->customers(companyId: 0)->customer(customerId: 123)->customFields()->list();
 *
 * // Access customer notes
 * $connector->customers(companyId: 0)->customer(customerId: 123)->notes()->list();
 *
 * // Access customer labor rates
 * $connector->customers(companyId: 0)->customer(customerId: 123)->laborRates()->list();
 */
final class CustomerScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $customerId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access contacts for this customer.
     */
    public function contacts(): ContactResource
    {
        return new ContactResource($this->connector, $this->companyId, $this->customerId);
    }

    /**
     * Navigate to a specific contact scope.
     */
    public function contact(int|string $contactId): ContactScope
    {
        return new ContactScope($this->connector, $this->companyId, $this->customerId, $contactId);
    }

    /**
     * Access contracts for this customer.
     */
    public function contracts(): ContractResource
    {
        return new ContractResource($this->connector, $this->companyId, $this->customerId);
    }

    /**
     * Navigate to a specific contract scope.
     */
    public function contract(int|string $contractId): ContractScope
    {
        return new ContractScope($this->connector, $this->companyId, $this->customerId, $contractId);
    }

    /**
     * Access custom fields for this customer.
     */
    public function customFields(): CustomerCustomFieldResource
    {
        return new CustomerCustomFieldResource($this->connector, $this->companyId, $this->customerId);
    }

    /**
     * Access notes for this customer.
     */
    public function notes(): CustomerNoteResource
    {
        return new CustomerNoteResource($this->connector, $this->companyId, $this->customerId);
    }

    /**
     * Access labor rates for this customer.
     */
    public function laborRates(): CustomerLaborRateResource
    {
        return new CustomerLaborRateResource($this->connector, $this->companyId, $this->customerId);
    }
}
