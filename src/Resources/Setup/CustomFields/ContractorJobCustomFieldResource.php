<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\ContractorJobs\CreateContractorJobCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\ContractorJobs\DeleteContractorJobCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\ContractorJobs\GetContractorJobCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\ContractorJobs\ListContractorJobCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\ContractorJobs\UpdateContractorJobCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AbstractCustomFieldResource;

/**
 * Resource for managing contractorJobs custom fields.
 */
final class ContractorJobCustomFieldResource extends AbstractCustomFieldResource
{
    protected function createListRequest(int|string $companyId): AbstractListCustomFieldsRequest
    {
        return new ListContractorJobCustomFieldsRequest($companyId);
    }

    protected function createGetRequest(int|string $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest
    {
        return new GetContractorJobCustomFieldRequest($companyId, $customFieldId);
    }

    protected function createCreateRequest(int|string $companyId, array $data): AbstractCreateCustomFieldRequest
    {
        return new CreateContractorJobCustomFieldRequest($companyId, $data);
    }

    protected function createUpdateRequest(int|string $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest
    {
        return new UpdateContractorJobCustomFieldRequest($companyId, $customFieldId, $data);
    }

    protected function createDeleteRequest(int|string $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest
    {
        return new DeleteContractorJobCustomFieldRequest($companyId, $customFieldId);
    }
}
