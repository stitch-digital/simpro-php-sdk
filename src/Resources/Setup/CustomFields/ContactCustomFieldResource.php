<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Contacts\CreateContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Contacts\DeleteContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Contacts\GetContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Contacts\ListContactCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Contacts\UpdateContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AbstractCustomFieldResource;

/**
 * Resource for managing contacts custom fields.
 */
final class ContactCustomFieldResource extends AbstractCustomFieldResource
{
    protected function createListRequest(int $companyId): AbstractListCustomFieldsRequest
    {
        return new ListContactCustomFieldsRequest($companyId);
    }

    protected function createGetRequest(int $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest
    {
        return new GetContactCustomFieldRequest($companyId, $customFieldId);
    }

    protected function createCreateRequest(int $companyId, array $data): AbstractCreateCustomFieldRequest
    {
        return new CreateContactCustomFieldRequest($companyId, $data);
    }

    protected function createUpdateRequest(int $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest
    {
        return new UpdateContactCustomFieldRequest($companyId, $customFieldId, $data);
    }

    protected function createDeleteRequest(int $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest
    {
        return new DeleteContactCustomFieldRequest($companyId, $customFieldId);
    }
}
