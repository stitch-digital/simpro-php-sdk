<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Tasks;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;

/**
 * Get a specific tasks custom field.
 */
final class GetTaskCustomFieldRequest extends AbstractGetCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'tasks';
    }
}
