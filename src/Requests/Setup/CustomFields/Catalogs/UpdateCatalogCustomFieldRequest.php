<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Catalogs;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;

/**
 * Update a catalog custom field.
 */
final class UpdateCatalogCustomFieldRequest extends AbstractUpdateCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'catalogs';
    }
}
