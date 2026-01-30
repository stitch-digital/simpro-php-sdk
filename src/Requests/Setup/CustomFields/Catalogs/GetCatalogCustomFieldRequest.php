<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Catalogs;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;

/**
 * Get a specific catalog custom field.
 */
final class GetCatalogCustomFieldRequest extends AbstractGetCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'catalogs';
    }
}
