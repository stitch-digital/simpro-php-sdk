<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Employees\Licences;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class Licence
{
    public function __construct(
        public int $id,
        public ?Reference $type,
        public ?string $licenceNo,
        public ?DateTimeImmutable $issueDate,
        public ?DateTimeImmutable $expiryDate,
        public ?string $notes,
        public ?bool $isVerified,
        public ?DateTimeImmutable $verifiedDate,
        public ?Reference $verifiedBy,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            type: isset($data['Type']) ? Reference::fromArray($data['Type']) : null,
            licenceNo: $data['LicenceNo'] ?? null,
            issueDate: isset($data['IssueDate']) ? new DateTimeImmutable($data['IssueDate']) : null,
            expiryDate: isset($data['ExpiryDate']) ? new DateTimeImmutable($data['ExpiryDate']) : null,
            notes: $data['Notes'] ?? null,
            isVerified: $data['IsVerified'] ?? null,
            verifiedDate: isset($data['VerifiedDate']) ? new DateTimeImmutable($data['VerifiedDate']) : null,
            verifiedBy: isset($data['VerifiedBy']) ? Reference::fromArray($data['VerifiedBy']) : null,
        );
    }
}
