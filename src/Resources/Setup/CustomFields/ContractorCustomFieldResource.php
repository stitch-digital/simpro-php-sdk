<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Contractors\CreateContractorCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Contractors\DeleteContractorCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Contractors\GetContractorCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Contractors\ListContractorCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Contractors\UpdateContractorCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AbstractCustomFieldResource;

/**
 * Resource for managing contractors custom fields.
 */
final class ContractorCustomFieldResource extends AbstractCustomFieldResource
{
    protected function createListRequest(int $companyId): AbstractListCustomFieldsRequest
    {
        return new ListContractorCustomFieldsRequest($companyId);
    }

    protected function createGetRequest(int $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest
    {
        return new GetContractorCustomFieldRequest($companyId, $customFieldId);
    }

    protected function createCreateRequest(int $companyId, array $data): AbstractCreateCustomFieldRequest
    {
        return new CreateContractorCustomFieldRequest($companyId, $data);
    }

    protected function createUpdateRequest(int $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest
    {
        return new UpdateContractorCustomFieldRequest($companyId, $customFieldId, $data);
    }

    protected function createDeleteRequest(int $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest
    {
        return new DeleteContractorCustomFieldRequest($companyId, $customFieldId);
    }
}
