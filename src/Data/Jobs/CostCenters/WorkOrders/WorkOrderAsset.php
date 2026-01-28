<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class WorkOrderAsset
{
    public function __construct(
        public int $id,
        public ?int $assetId,
        public ?string $assetName,
        public ?int $assetTypeId,
        public ?string $assetTypeName,
        public ?string $serialNumber,
        public ?DateTimeImmutable $dateCreated,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            assetId: isset($data['Asset']['ID']) ? (int) $data['Asset']['ID'] : null,
            assetName: $data['Asset']['Name'] ?? null,
            assetTypeId: isset($data['AssetType']['ID']) ? (int) $data['AssetType']['ID'] : null,
            assetTypeName: $data['AssetType']['Name'] ?? null,
            serialNumber: $data['SerialNumber'] ?? null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
        );
    }
}
