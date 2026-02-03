<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListDetailedCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\CustomerContacts\CreateCustomerContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\CustomerContacts\DeleteCustomerContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\CustomerContacts\GetCustomerContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\CustomerContacts\ListCustomerContactCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\CustomerContacts\ListDetailedCustomerContactCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\CustomerContacts\UpdateCustomerContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AbstractCustomFieldResource;

/**
 * Resource for managing customerContacts custom fields.
 */
final class CustomerContactCustomFieldResource extends AbstractCustomFieldResource
{
    protected function createListRequest(int $companyId): AbstractListCustomFieldsRequest
    {
        return new ListCustomerContactCustomFieldsRequest($companyId);
    }

    protected function createListDetailedRequest(int $companyId): AbstractListDetailedCustomFieldsRequest
    {
        return new ListDetailedCustomerContactCustomFieldsRequest($companyId);
    }

    protected function createGetRequest(int $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest
    {
        return new GetCustomerContactCustomFieldRequest($companyId, $customFieldId);
    }

    protected function createCreateRequest(int $companyId, array $data): AbstractCreateCustomFieldRequest
    {
        return new CreateCustomerContactCustomFieldRequest($companyId, $data);
    }

    protected function createUpdateRequest(int $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest
    {
        return new UpdateCustomerContactCustomFieldRequest($companyId, $customFieldId, $data);
    }

    protected function createDeleteRequest(int $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest
    {
        return new DeleteCustomerContactCustomFieldRequest($companyId, $customFieldId);
    }
}
