<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Customers\CreateCustomerCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Customers\DeleteCustomerCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Customers\GetCustomerCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Customers\ListCustomerCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Customers\UpdateCustomerCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AbstractCustomFieldResource;

/**
 * Resource for managing customers custom fields.
 */
final class CustomerCustomFieldResource extends AbstractCustomFieldResource
{
    protected function createListRequest(int $companyId): AbstractListCustomFieldsRequest
    {
        return new ListCustomerCustomFieldsRequest($companyId);
    }

    protected function createGetRequest(int $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest
    {
        return new GetCustomerCustomFieldRequest($companyId, $customFieldId);
    }

    protected function createCreateRequest(int $companyId, array $data): AbstractCreateCustomFieldRequest
    {
        return new CreateCustomerCustomFieldRequest($companyId, $data);
    }

    protected function createUpdateRequest(int $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest
    {
        return new UpdateCustomerCustomFieldRequest($companyId, $customFieldId, $data);
    }

    protected function createDeleteRequest(int $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest
    {
        return new DeleteCustomerCustomFieldRequest($companyId, $customFieldId);
    }
}
