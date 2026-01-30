<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Projects;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;

/**
 * Create a projects custom field.
 */
final class CreateProjectCustomFieldRequest extends AbstractCreateCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'projects';
    }
}
