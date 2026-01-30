<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Vendors;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;

/**
 * Update a vendors custom field.
 */
final class UpdateVendorCustomFieldRequest extends AbstractUpdateCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'vendors';
    }
}
