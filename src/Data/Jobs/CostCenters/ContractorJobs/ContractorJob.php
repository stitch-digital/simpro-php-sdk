<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\ContractorJobs;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class ContractorJob
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?int $vendorId,
        public ?string $vendorName,
        public ?string $status,
        public ?string $description,
        public ?float $totalCost,
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
            vendorId: isset($data['Vendor']['ID']) ? (int) $data['Vendor']['ID'] : null,
            vendorName: $data['Vendor']['Name'] ?? null,
            status: $data['Status'] ?? null,
            description: $data['Description'] ?? null,
            totalCost: isset($data['TotalCost']) ? (float) $data['TotalCost'] : null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
