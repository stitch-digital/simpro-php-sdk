<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractCreateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractUpdateCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\SiteContacts\CreateSiteContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\SiteContacts\DeleteSiteContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\SiteContacts\GetSiteContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\SiteContacts\ListSiteContactCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\SiteContacts\UpdateSiteContactCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AbstractCustomFieldResource;

/**
 * Resource for managing siteContacts custom fields.
 */
final class SiteContactCustomFieldResource extends AbstractCustomFieldResource
{
    protected function createListRequest(int $companyId): AbstractListCustomFieldsRequest
    {
        return new ListSiteContactCustomFieldsRequest($companyId);
    }

    protected function createGetRequest(int $companyId, int|string $customFieldId): AbstractGetCustomFieldRequest
    {
        return new GetSiteContactCustomFieldRequest($companyId, $customFieldId);
    }

    protected function createCreateRequest(int $companyId, array $data): AbstractCreateCustomFieldRequest
    {
        return new CreateSiteContactCustomFieldRequest($companyId, $data);
    }

    protected function createUpdateRequest(int $companyId, int|string $customFieldId, array $data): AbstractUpdateCustomFieldRequest
    {
        return new UpdateSiteContactCustomFieldRequest($companyId, $customFieldId, $data);
    }

    protected function createDeleteRequest(int $companyId, int|string $customFieldId): AbstractDeleteCustomFieldRequest
    {
        return new DeleteSiteContactCustomFieldRequest($companyId, $customFieldId);
    }
}
