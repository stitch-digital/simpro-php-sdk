<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Customers;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Customers\ContactCustomFieldResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific contact, providing access to nested resources.
 *
 * @example
 * // Access contact custom fields
 * $connector->customers(companyId: 0)->customer(customerId: 123)->contact(contactId: 1)->customFields()->list();
 */
final class ContactScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contactId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access custom fields for this contact.
     */
    public function customFields(): ContactCustomFieldResource
    {
        return new ContactCustomFieldResource($this->connector, $this->companyId, $this->customerId, $this->contactId);
    }
}
