<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\SiteContacts;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;

/**
 * Create a siteContacts custom field.
 */
final class CreateSiteContactCustomFieldRequest extends AbstractCreateCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'siteContacts';
    }
}
