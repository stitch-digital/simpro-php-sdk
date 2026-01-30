<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Employees;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;

/**
 * Create a employees custom field.
 */
final class CreateEmployeeCustomFieldRequest extends AbstractCreateCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'employees';
    }
}
