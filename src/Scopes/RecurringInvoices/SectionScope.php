<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\RecurringInvoices;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenterResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific recurring invoice section, providing access to nested resources.
 *
 * @example
 * // Access cost centers for this section
 * $connector->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenters()->list();
 *
 * // Navigate to a specific cost center
 * $connector->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->oneOffs()->list();
 */
final class SectionScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $recurringInvoiceId,
        private readonly int|string $sectionId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access cost centers for this section.
     */
    public function costCenters(): CostCenterResource
    {
        return new CostCenterResource($this->connector, $this->companyId, $this->recurringInvoiceId, $this->sectionId);
    }

    /**
     * Navigate to a specific cost center scope.
     */
    public function costCenter(int|string $costCenterId): CostCenterScope
    {
        return new CostCenterScope($this->connector, $this->companyId, $this->recurringInvoiceId, $this->sectionId, $costCenterId);
    }
}
