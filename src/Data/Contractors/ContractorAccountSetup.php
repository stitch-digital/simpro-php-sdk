<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Contractors;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class ContractorAccountSetup
{
    public function __construct(
        public ?string $username,
        public ?bool $isMobility,
        public ?Reference $securityGroup,
        public ?Reference $mobileSecurityGroup,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            username: $data['Username'] ?? null,
            isMobility: $data['IsMobility'] ?? null,
            securityGroup: ! empty($data['SecurityGroup']) ? Reference::fromArray($data['SecurityGroup']) : null,
            mobileSecurityGroup: ! empty($data['MobileSecurityGroup']) ? Reference::fromArray($data['MobileSecurityGroup']) : null,
        );
    }
}
