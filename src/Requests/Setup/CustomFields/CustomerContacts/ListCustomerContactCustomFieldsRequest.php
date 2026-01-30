<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\CustomerContacts;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;

/**
 * List all customerContacts custom fields.
 */
final class ListCustomerContactCustomFieldsRequest extends AbstractListCustomFieldsRequest
{
    protected function getResourcePath(): string
    {
        return 'customerContacts';
    }
}
