<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Sites;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListDetailedCustomFieldsRequest;

/**
 * List all site custom fields with full details.
 */
final class ListDetailedSiteCustomFieldsRequest extends AbstractListDetailedCustomFieldsRequest
{
    protected function getResourcePath(): string
    {
        return 'sites';
    }
}
