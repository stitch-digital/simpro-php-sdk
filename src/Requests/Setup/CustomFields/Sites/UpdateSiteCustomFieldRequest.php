<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Sites;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;

/**
 * Update a sites custom field.
 */
final class UpdateSiteCustomFieldRequest extends AbstractUpdateCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'sites';
    }
}
