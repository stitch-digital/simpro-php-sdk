<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\WorkOrders\CreateWorkOrderCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\WorkOrders\DeleteWorkOrderCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\WorkOrders\GetWorkOrderCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\WorkOrders\ListWorkOrderCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\WorkOrders\UpdateWorkOrderCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AbstractCustomFieldResource;

/**
 * Resource for managing workOrders custom fields.
 */
final class WorkOrderCustomFieldResource extends AbstractCustomFieldResource
{
    protected function createListRequest(int $companyId): AbstractListCustomFieldsRequest
    {
        return new ListWorkOrderCustomFieldsRequest($companyId);
    }

    protected function createGetRequest(int $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest
    {
        return new GetWorkOrderCustomFieldRequest($companyId, $customFieldId);
    }

    protected function createCreateRequest(int $companyId, array $data): AbstractCreateCustomFieldRequest
    {
        return new CreateWorkOrderCustomFieldRequest($companyId, $data);
    }

    protected function createUpdateRequest(int $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest
    {
        return new UpdateWorkOrderCustomFieldRequest($companyId, $customFieldId, $data);
    }

    protected function createDeleteRequest(int $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest
    {
        return new DeleteWorkOrderCustomFieldRequest($companyId, $customFieldId);
    }
}
