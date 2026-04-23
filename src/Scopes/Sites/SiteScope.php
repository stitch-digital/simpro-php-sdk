<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Sites;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Sites\SiteContactResource;
use Simpro\PhpSdk\Simpro\Resources\Sites\SiteCustomFieldResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific site, providing access to nested resources.
 *
 * @example
 * // Access site contacts
 * $connector->sites(companyId: 0)->site(siteId: 123)->contacts()->list();
 *
 * // Access site custom fields
 * $connector->sites(companyId: 0)->site(siteId: 123)->customFields()->list();
 */
final class SiteScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $siteId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access contacts for this site.
     */
    public function contacts(): SiteContactResource
    {
        return new SiteContactResource($this->connector, $this->companyId, $this->siteId);
    }

    /**
     * Navigate to a specific contact scope.
     */
    public function contact(int|string $contactId): SiteContactScope
    {
        return new SiteContactScope($this->connector, $this->companyId, $this->siteId, $contactId);
    }

    /**
     * Access custom fields for this site.
     */
    public function customFields(): SiteCustomFieldResource
    {
        return new SiteCustomFieldResource($this->connector, $this->companyId, $this->siteId);
    }
}
