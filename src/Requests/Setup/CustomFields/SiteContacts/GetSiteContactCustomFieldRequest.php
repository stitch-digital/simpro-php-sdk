<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\SiteContacts;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;

/**
 * Get a specific siteContacts custom field.
 */
final class GetSiteContactCustomFieldRequest extends AbstractGetCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'siteContacts';
    }
}
