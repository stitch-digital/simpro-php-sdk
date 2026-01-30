<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Sites;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;

/**
 * Create a sites custom field.
 */
final class CreateSiteCustomFieldRequest extends AbstractCreateCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'sites';
    }
}
