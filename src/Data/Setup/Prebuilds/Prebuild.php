<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup\Prebuilds;

use DateTimeImmutable;

final readonly class Prebuild
{
    public function __construct(
        public int $id,
        public ?int $groupId,
        public ?string $groupName,
        public ?int $parentGroupId,
        public ?string $parentGroupName,
        public ?string $partNo,
        public string $name,
        public ?int $taxCodeId,
        public ?string $taxCode,
        public ?float $taxRate,
        public float $materials,
        public float $labour,
        public float $materialMarkup,
        public float $labourMarkup,
        public float $profit,
        public float $margin,
        public float $totalEx,
        public float $totalInc,
        public bool $archived,
        public ?DateTimeImmutable $dateModified,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $group = $data['Group'] ?? [];
        $parent = $group['ParentGroup'] ?? [];
        $tax = $data['SalesTaxCode'] ?? [];

        return new self(
            id: (int) $data['ID'],
            groupId: isset($group['ID']) ? (int) $group['ID'] : null,
            groupName: $group['Name'] ?? null,
            parentGroupId: isset($parent['ID']) ? (int) $parent['ID'] : null,
            parentGroupName: $parent['Name'] ?? null,
            partNo: $data['PartNo'] ?? null,
            name: (string) ($data['Name'] ?? ''),
            taxCodeId: isset($tax['ID']) ? (int) $tax['ID'] : null,
            taxCode: $tax['Code'] ?? null,
            taxRate: isset($tax['Rate']) ? (float) $tax['Rate'] : null,
            materials: (float) ($data['Materials'] ?? 0),
            labour: (float) ($data['Labour'] ?? 0),
            materialMarkup: (float) ($data['MaterialMarkup'] ?? 0),
            labourMarkup: (float) ($data['LabourMarkup'] ?? 0),
            profit: (float) ($data['Profit'] ?? 0),
            margin: (float) ($data['Margin'] ?? 0),
            totalEx: (float) ($data['TotalEx'] ?? 0),
            totalInc: (float) ($data['TotalInc'] ?? 0),
            archived: (bool) ($data['Archived'] ?? false),
            dateModified: ! empty($data['DateModified'])
                ? new DateTimeImmutable($data['DateModified'])
                : null,
        );
    }
}
