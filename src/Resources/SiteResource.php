<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Sites\ListSitesDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Sites\ListSitesRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class SiteResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all sites.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListSitesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all sites with all available columns.
     *
     * Returns SiteListDetailedItem DTOs with all available columns including:
     * Address, BillingAddress, BillingContact, PrimaryContact, PublicNotes,
     * PrivateNotes, Zone, PreferredTechs, PreferredTechnicians, DateModified,
     * Customers, CustomFields, and Rates.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListSitesDetailedRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }
}
