<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\SiteContacts;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListDetailedCustomFieldsRequest;

/**
 * List all site contact custom fields with full details.
 */
final class ListDetailedSiteContactCustomFieldsRequest extends AbstractListDetailedCustomFieldsRequest
{
    protected function getResourcePath(): string
    {
        return 'siteContacts';
    }
}
