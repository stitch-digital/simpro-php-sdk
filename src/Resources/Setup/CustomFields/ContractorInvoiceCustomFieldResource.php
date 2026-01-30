<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\ContractorInvoices\CreateContractorInvoiceCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\ContractorInvoices\DeleteContractorInvoiceCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\ContractorInvoices\GetContractorInvoiceCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\ContractorInvoices\ListContractorInvoiceCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\ContractorInvoices\UpdateContractorInvoiceCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AbstractCustomFieldResource;

/**
 * Resource for managing contractorInvoices custom fields.
 */
final class ContractorInvoiceCustomFieldResource extends AbstractCustomFieldResource
{
    protected function createListRequest(int|string $companyId): AbstractListCustomFieldsRequest
    {
        return new ListContractorInvoiceCustomFieldsRequest($companyId);
    }

    protected function createGetRequest(int|string $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest
    {
        return new GetContractorInvoiceCustomFieldRequest($companyId, $customFieldId);
    }

    protected function createCreateRequest(int|string $companyId, array $data): AbstractCreateCustomFieldRequest
    {
        return new CreateContractorInvoiceCustomFieldRequest($companyId, $data);
    }

    protected function createUpdateRequest(int|string $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest
    {
        return new UpdateContractorInvoiceCustomFieldRequest($companyId, $customFieldId, $data);
    }

    protected function createDeleteRequest(int|string $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest
    {
        return new DeleteContractorInvoiceCustomFieldRequest($companyId, $customFieldId);
    }
}
