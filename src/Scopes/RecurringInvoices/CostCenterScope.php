<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\RecurringInvoices;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters\CatalogResource;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters\LaborResource;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters\OneOffResource;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters\PrebuildResource;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters\ServiceFeeResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific recurring invoice cost center, providing access to its line items.
 *
 * @example
 * // Access one-off items for this cost center
 * $connector->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->oneOffs()->list();
 */
final class CostCenterScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $recurringInvoiceId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access catalog (material) items for this cost center.
     */
    public function catalogs(): CatalogResource
    {
        return new CatalogResource($this->connector, $this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access labor items for this cost center.
     */
    public function labor(): LaborResource
    {
        return new LaborResource($this->connector, $this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access one-off items for this cost center.
     */
    public function oneOffs(): OneOffResource
    {
        return new OneOffResource($this->connector, $this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access prebuild items for this cost center.
     */
    public function prebuilds(): PrebuildResource
    {
        return new PrebuildResource($this->connector, $this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access service fee items for this cost center.
     */
    public function serviceFees(): ServiceFeeResource
    {
        return new ServiceFeeResource($this->connector, $this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId);
    }
}
