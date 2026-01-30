<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Tasks;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;

/**
 * List all tasks custom fields.
 */
final class ListTaskCustomFieldsRequest extends AbstractListCustomFieldsRequest
{
    protected function getResourcePath(): string
    {
        return 'tasks';
    }
}
