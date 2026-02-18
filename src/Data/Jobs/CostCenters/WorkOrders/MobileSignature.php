<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class MobileSignature
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $signedByName,
        public ?string $signedByEmail,
        public ?string $signatureUrl,
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
            name: $data['Name'] ?? null,
            signedByName: $data['SignedBy']['Name'] ?? null,
            signedByEmail: $data['SignedBy']['Email'] ?? null,
            signatureUrl: $data['SignatureUrl'] ?? null,
            dateCreated: ! empty($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
        );
    }
}
