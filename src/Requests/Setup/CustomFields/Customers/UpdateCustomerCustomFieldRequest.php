<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Customers;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;

/**
 * Update a customers custom field.
 */
final class UpdateCustomerCustomFieldRequest extends AbstractUpdateCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'customers';
    }
}
