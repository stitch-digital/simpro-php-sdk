<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Contractors;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class ContractorUserProfile
{
    public function __construct(
        public ?bool $isSalesperson,
        public ?bool $isProjectManager,
        public ?Reference $storageDevice,
        public ?string $preferredLanguage,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isSalesperson: $data['IsSalesperson'] ?? null,
            isProjectManager: $data['IsProjectManager'] ?? null,
            storageDevice: ! empty($data['StorageDevice']) ? Reference::fromArray($data['StorageDevice']) : null,
            preferredLanguage: $data['PreferredLanguage'] ?? null,
        );
    }
}
