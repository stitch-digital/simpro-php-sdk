<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\VendorContacts;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;

/**
 * List all vendorContacts custom fields.
 */
final class ListVendorContactCustomFieldsRequest extends AbstractListCustomFieldsRequest
{
    protected function getResourcePath(): string
    {
        return 'vendorContacts';
    }
}
