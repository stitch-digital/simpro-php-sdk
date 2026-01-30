<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Employees;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;

/**
 * Delete a employees custom field.
 */
final class DeleteEmployeeCustomFieldRequest extends AbstractDeleteCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'employees';
    }
}
