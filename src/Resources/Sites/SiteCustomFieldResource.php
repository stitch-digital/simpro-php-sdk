<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Sites;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Sites\CustomFields\SiteCustomFieldValue;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Sites\CustomFields\GetSiteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\CustomFields\ListSiteCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\CustomFields\UpdateSiteCustomFieldRequest;

/**
 * Resource for managing custom fields on a specific site.
 *
 * @property AbstractSimproConnector $connector
 */
final class SiteCustomFieldResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $siteId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all custom fields for this site.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListSiteCustomFieldsRequest($this->companyId, $this->siteId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific custom field.
     */
    public function get(int|string $customFieldId): SiteCustomFieldValue
    {
        $request = new GetSiteCustomFieldRequest($this->companyId, $this->siteId, $customFieldId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a custom field value.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customFieldId, array $data): Response
    {
        $request = new UpdateSiteCustomFieldRequest($this->companyId, $this->siteId, $customFieldId, $data);

        return $this->connector->send($request);
    }
}
