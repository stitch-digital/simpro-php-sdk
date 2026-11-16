<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoiceCostCenters;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * A cost center line from the company-wide recurring invoice cost center list.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/recurringInvoiceCostCenters/
 */
final readonly class RecurringInvoiceCostCenterListItem
{
    public function __construct(
        public int $id,
        public ?Reference $costCenter,
        public ?string $name,
        public ?RecurringInvoiceCostCenterReference $recurringInvoice,
        public ?Reference $section,
        public ?DateTimeImmutable $dateModified,
        public ?string $href,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            costCenter: ! empty($data['CostCenter']) ? Reference::fromArray($data['CostCenter']) : null,
            name: $data['Name'] ?? null,
            recurringInvoice: ! empty($data['RecurringInvoice']) ? RecurringInvoiceCostCenterReference::fromArray($data['RecurringInvoice']) : null,
            section: ! empty($data['Section']) ? Reference::fromArray($data['Section']) : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            href: $data['_href'] ?? null,
        );
    }
}
