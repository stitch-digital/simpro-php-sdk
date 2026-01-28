<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobCustomField
{
    /**
     * @param  array<string>|null  $listItems
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $type,
        public ?bool $isMandatory,
        public ?array $listItems,
        public mixed $value,
    ) {}

    public static function fromArray(array $data): self
    {
        // Handle both flat format and nested CustomField format
        if (isset($data['CustomField'])) {
            $customField = $data['CustomField'];

            return new self(
                id: $customField['ID'],
                name: $customField['Name'] ?? '',
                type: $customField['Type'] ?? null,
                isMandatory: $customField['IsMandatory'] ?? null,
                listItems: $customField['ListItems'] ?? null,
                value: $data['Value'] ?? null,
            );
        }

        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? '',
            type: $data['Type'] ?? null,
            isMandatory: $data['IsMandatory'] ?? null,
            listItems: $data['ListItems'] ?? null,
            value: $data['Value'] ?? null,
        );
    }
}
