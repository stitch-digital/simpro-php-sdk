<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Employees\Licences;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class LicenceListItem
{
    public function __construct(
        public int $id,
        public ?Reference $type,
        public ?string $licenceNo,
        public ?DateTimeImmutable $expiryDate,
        public ?bool $isVerified,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            type: ! empty($data['Type']) ? Reference::fromArray($data['Type']) : null,
            licenceNo: $data['LicenceNo'] ?? null,
            expiryDate: ! empty($data['ExpiryDate']) ? new DateTimeImmutable($data['ExpiryDate']) : null,
            isVerified: $data['IsVerified'] ?? null,
        );
    }
}
