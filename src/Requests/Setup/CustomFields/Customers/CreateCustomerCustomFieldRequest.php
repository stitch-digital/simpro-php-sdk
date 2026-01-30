<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Customers;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;

/**
 * Create a customers custom field.
 */
final class CreateCustomerCustomFieldRequest extends AbstractCreateCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'customers';
    }
}
