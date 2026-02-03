<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListDetailedCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\VendorContacts\CreateVendorContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\VendorContacts\DeleteVendorContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\VendorContacts\GetVendorContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\VendorContacts\ListDetailedVendorContactCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\VendorContacts\ListVendorContactCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\VendorContacts\UpdateVendorContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AbstractCustomFieldResource;

/**
 * Resource for managing vendorContacts custom fields.
 */
final class VendorContactCustomFieldResource extends AbstractCustomFieldResource
{
    protected function createListRequest(int $companyId): AbstractListCustomFieldsRequest
    {
        return new ListVendorContactCustomFieldsRequest($companyId);
    }

    protected function createListDetailedRequest(int $companyId): AbstractListDetailedCustomFieldsRequest
    {
        return new ListDetailedVendorContactCustomFieldsRequest($companyId);
    }

    protected function createGetRequest(int $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest
    {
        return new GetVendorContactCustomFieldRequest($companyId, $customFieldId);
    }

    protected function createCreateRequest(int $companyId, array $data): AbstractCreateCustomFieldRequest
    {
        return new CreateVendorContactCustomFieldRequest($companyId, $data);
    }

    protected function createUpdateRequest(int $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest
    {
        return new UpdateVendorContactCustomFieldRequest($companyId, $customFieldId, $data);
    }

    protected function createDeleteRequest(int $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest
    {
        return new DeleteVendorContactCustomFieldRequest($companyId, $customFieldId);
    }
}
