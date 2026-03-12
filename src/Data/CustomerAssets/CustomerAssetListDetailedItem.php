<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\CustomerAssets;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class CustomerAssetListDetailedItem
{
    /**
     * @param  array<CustomField>|null  $customFields
     */
    public function __construct(
        public int $id,
        public ?Reference $assetType = null,
        public ?int $displayOrder = null,
        public ?CustomerAssetContract $customerContract = null,
        public ?string $startDate = null,
        public ?CustomerAssetLastTest $lastTest = null,
        public ?bool $archived = null,
        public ?array $customFields = null,
        public ?int $parentId = null,
        public ?DateTimeImmutable $dateModified = null,
        public ?Reference $site = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            assetType: isset($data['AssetType']) && is_array($data['AssetType'])
                ? Reference::fromArray($data['AssetType'])
                : null,
            displayOrder: $data['DisplayOrder'] ?? null,
            customerContract: isset($data['CustomerContract']) && is_array($data['CustomerContract'])
                ? CustomerAssetContract::fromArray($data['CustomerContract'])
                : null,
            startDate: $data['StartDate'] ?? null,
            lastTest: isset($data['LastTest']) && is_array($data['LastTest'])
                ? CustomerAssetLastTest::fromArray($data['LastTest'])
                : null,
            archived: $data['Archived'] ?? null,
            customFields: isset($data['CustomFields']) && is_array($data['CustomFields'])
                ? array_map(fn (array $item) => CustomField::fromArray($item), $data['CustomFields'])
                : null,
            parentId: $data['ParentID'] ?? null,
            dateModified: isset($data['DateModified'])
                ? new DateTimeImmutable($data['DateModified'])
                : null,
            site: isset($data['Site']) && is_array($data['Site'])
                ? Reference::fromArray($data['Site'])
                : null,
        );
    }
}
