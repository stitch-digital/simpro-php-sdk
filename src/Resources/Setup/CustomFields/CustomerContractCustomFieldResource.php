<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\CustomerContracts\CreateCustomerContractCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\CustomerContracts\DeleteCustomerContractCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\CustomerContracts\GetCustomerContractCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\CustomerContracts\ListCustomerContractCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\CustomerContracts\UpdateCustomerContractCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AbstractCustomFieldResource;

/**
 * Resource for managing customerContracts custom fields.
 */
final class CustomerContractCustomFieldResource extends AbstractCustomFieldResource
{
    protected function createListRequest(int|string $companyId): AbstractListCustomFieldsRequest
    {
        return new ListCustomerContractCustomFieldsRequest($companyId);
    }

    protected function createGetRequest(int|string $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest
    {
        return new GetCustomerContractCustomFieldRequest($companyId, $customFieldId);
    }

    protected function createCreateRequest(int|string $companyId, array $data): AbstractCreateCustomFieldRequest
    {
        return new CreateCustomerContractCustomFieldRequest($companyId, $data);
    }

    protected function createUpdateRequest(int|string $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest
    {
        return new UpdateCustomerContractCustomFieldRequest($companyId, $customFieldId, $data);
    }

    protected function createDeleteRequest(int|string $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest
    {
        return new DeleteCustomerContractCustomFieldRequest($companyId, $customFieldId);
    }
}
