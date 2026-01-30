<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Tasks;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;

/**
 * Delete a tasks custom field.
 */
final class DeleteTaskCustomFieldRequest extends AbstractDeleteCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'tasks';
    }
}
