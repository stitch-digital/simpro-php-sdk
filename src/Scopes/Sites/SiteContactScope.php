<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Sites;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific site contact, providing access to nested resources.
 *
 * @example
 * // Access site contact custom fields
 * $connector->sites(companyId: 0)->site(siteId: 123)->contact(contactId: 1)->customFields()->list();
 */
final class SiteContactScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $siteId,
        private readonly int|string $contactId,
    ) {
        parent::__construct($connector, $companyId);
    }
}
