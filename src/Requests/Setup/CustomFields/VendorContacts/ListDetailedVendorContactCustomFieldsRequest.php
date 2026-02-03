<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\VendorContacts;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListDetailedCustomFieldsRequest;

/**
 * List all vendor contact custom fields with full details.
 */
final class ListDetailedVendorContactCustomFieldsRequest extends AbstractListDetailedCustomFieldsRequest
{
    protected function getResourcePath(): string
    {
        return 'vendorContacts';
    }
}
