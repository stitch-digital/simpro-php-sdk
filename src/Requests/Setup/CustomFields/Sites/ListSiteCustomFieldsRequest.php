<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Sites;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;

/**
 * List all sites custom fields.
 */
final class ListSiteCustomFieldsRequest extends AbstractListCustomFieldsRequest
{
    protected function getResourcePath(): string
    {
        return 'sites';
    }
}
