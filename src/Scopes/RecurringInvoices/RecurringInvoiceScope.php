<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\RecurringInvoices;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\RecurringInvoiceSectionResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific recurring invoice, providing access to nested resources.
 *
 * @example
 * // Access sections
 * $connector->recurringInvoices(companyId: 0)->recurringInvoice(501)->sections()->list();
 *
 * // Navigate to a specific section
 * $connector->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(1)->costCenters()->list();
 */
final class RecurringInvoiceScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $recurringInvoiceId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access sections for this recurring invoice.
     */
    public function sections(): RecurringInvoiceSectionResource
    {
        return new RecurringInvoiceSectionResource($this->connector, $this->companyId, $this->recurringInvoiceId);
    }

    /**
     * Navigate to a specific section scope.
     */
    public function section(int|string $sectionId): SectionScope
    {
        return new SectionScope($this->connector, $this->companyId, $this->recurringInvoiceId, $sectionId);
    }
}
