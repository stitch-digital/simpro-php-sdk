<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\CustomerContacts;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;

/**
 * Delete a customerContacts custom field.
 */
final class DeleteCustomerContactCustomFieldRequest extends AbstractDeleteCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'customerContacts';
    }
}
