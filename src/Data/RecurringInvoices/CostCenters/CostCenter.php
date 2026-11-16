<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Money;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Jobs\JobTotals;

/**
 * A cost center on a recurring invoice section: the line's Simpro cost centre, its totals and its lock state.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}
 */
final readonly class CostCenter
{
    public function __construct(
        public int $id,
        public ?Reference $costCenter,
        public ?int $recurringId,
        public ?string $name,
        public ?string $header,
        public ?Reference $site,
        public ?string $description,
        public ?string $notes,
        public ?string $orderNo,
        public ?int $displayOrder,
        public ?bool $itemsLocked,
        public ?bool $revizedLocked,
        public ?Money $total,
        public ?JobTotals $totals,
        public ?DateTimeImmutable $dateModified,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            costCenter: ! empty($data['CostCenter']) ? Reference::fromArray($data['CostCenter']) : null,
            recurringId: isset($data['RecurringID']) ? (int) $data['RecurringID'] : null,
            name: $data['Name'] ?? null,
            header: $data['Header'] ?? null,
            site: ! empty($data['Site']) ? Reference::fromArray($data['Site']) : null,
            description: $data['Description'] ?? null,
            notes: $data['Notes'] ?? null,
            orderNo: $data['OrderNo'] ?? null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
            itemsLocked: isset($data['ItemsLocked']) ? (bool) $data['ItemsLocked'] : null,
            revizedLocked: isset($data['RevizedLocked']) ? (bool) $data['RevizedLocked'] : null,
            total: ! empty($data['Total']) && is_array($data['Total']) ? Money::fromArray($data['Total']) : null,
            totals: ! empty($data['Totals']) ? JobTotals::fromArray($data['Totals']) : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
