<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Employees\CreateEmployeeCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Employees\DeleteEmployeeCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Employees\GetEmployeeCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Employees\ListEmployeeCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Employees\UpdateEmployeeCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AbstractCustomFieldResource;

/**
 * Resource for managing employees custom fields.
 */
final class EmployeeCustomFieldResource extends AbstractCustomFieldResource
{
    protected function createListRequest(int|string $companyId): AbstractListCustomFieldsRequest
    {
        return new ListEmployeeCustomFieldsRequest($companyId);
    }

    protected function createGetRequest(int|string $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest
    {
        return new GetEmployeeCustomFieldRequest($companyId, $customFieldId);
    }

    protected function createCreateRequest(int|string $companyId, array $data): AbstractCreateCustomFieldRequest
    {
        return new CreateEmployeeCustomFieldRequest($companyId, $data);
    }

    protected function createUpdateRequest(int|string $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest
    {
        return new UpdateEmployeeCustomFieldRequest($companyId, $customFieldId, $data);
    }

    protected function createDeleteRequest(int|string $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest
    {
        return new DeleteEmployeeCustomFieldRequest($companyId, $customFieldId);
    }
}
