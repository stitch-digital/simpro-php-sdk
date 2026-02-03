<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Catalogs;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListDetailedCustomFieldsRequest;

/**
 * List all catalog custom fields with full details.
 */
final class ListDetailedCatalogCustomFieldsRequest extends AbstractListDetailedCustomFieldsRequest
{
    protected function getResourcePath(): string
    {
        return 'catalogs';
    }
}
