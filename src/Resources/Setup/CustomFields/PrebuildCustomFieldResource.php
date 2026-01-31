<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Prebuilds\CreatePrebuildCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Prebuilds\DeletePrebuildCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Prebuilds\GetPrebuildCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Prebuilds\ListPrebuildCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Prebuilds\UpdatePrebuildCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AbstractCustomFieldResource;

/**
 * Resource for managing prebuilds custom fields.
 */
final class PrebuildCustomFieldResource extends AbstractCustomFieldResource
{
    protected function createListRequest(int $companyId): AbstractListCustomFieldsRequest
    {
        return new ListPrebuildCustomFieldsRequest($companyId);
    }

    protected function createGetRequest(int $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest
    {
        return new GetPrebuildCustomFieldRequest($companyId, $customFieldId);
    }

    protected function createCreateRequest(int $companyId, array $data): AbstractCreateCustomFieldRequest
    {
        return new CreatePrebuildCustomFieldRequest($companyId, $data);
    }

    protected function createUpdateRequest(int $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest
    {
        return new UpdatePrebuildCustomFieldRequest($companyId, $customFieldId, $data);
    }

    protected function createDeleteRequest(int $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest
    {
        return new DeletePrebuildCustomFieldRequest($companyId, $customFieldId);
    }
}
