<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\VendorOrders;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;

/**
 * Update a vendorOrders custom field.
 */
final class UpdateVendorOrderCustomFieldRequest extends AbstractUpdateCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'vendorOrders';
    }
}
