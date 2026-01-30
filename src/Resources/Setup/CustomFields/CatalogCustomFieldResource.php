<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Catalogs\CreateCatalogCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Catalogs\DeleteCatalogCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Catalogs\GetCatalogCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Catalogs\ListCatalogCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Catalogs\UpdateCatalogCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AbstractCustomFieldResource;

/**
 * Resource for managing catalog custom fields.
 */
final class CatalogCustomFieldResource extends AbstractCustomFieldResource
{
    protected function createListRequest(int|string $companyId): AbstractListCustomFieldsRequest
    {
        return new ListCatalogCustomFieldsRequest($companyId);
    }

    protected function createGetRequest(int|string $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest
    {
        return new GetCatalogCustomFieldRequest($companyId, $customFieldId);
    }

    protected function createCreateRequest(int|string $companyId, array $data): AbstractCreateCustomFieldRequest
    {
        return new CreateCatalogCustomFieldRequest($companyId, $data);
    }

    protected function createUpdateRequest(int|string $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest
    {
        return new UpdateCatalogCustomFieldRequest($companyId, $customFieldId, $data);
    }

    protected function createDeleteRequest(int|string $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest
    {
        return new DeleteCatalogCustomFieldRequest($companyId, $customFieldId);
    }
}
