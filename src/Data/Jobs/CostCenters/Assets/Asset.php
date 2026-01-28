<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Assets;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class Asset
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?int $assetTypeId,
        public ?string $assetTypeName,
        public ?string $serialNumber,
        public ?DateTimeImmutable $dateCreated,
        public ?DateTimeImmutable $dateModified,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? null,
            assetTypeId: isset($data['AssetType']['ID']) ? (int) $data['AssetType']['ID'] : null,
            assetTypeName: $data['AssetType']['Name'] ?? null,
            serialNumber: $data['SerialNumber'] ?? null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
