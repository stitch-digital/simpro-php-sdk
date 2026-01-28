<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Common;

/**
 * Common DTO for custom field values.
 *
 * Used by jobs, quotes, customers, and other entities that support custom fields.
 */
final readonly class CustomField
{
    /**
     * @param  array<string>|null  $listItems
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $type = null,
        public ?bool $isMandatory = null,
        public ?array $listItems = null,
        public mixed $value = null,
    ) {}

    public static function fromArray(array $data): self
    {
        // Handle both flat format and nested CustomField format
        if (isset($data['CustomField'])) {
            $customField = $data['CustomField'];

            return new self(
                id: (int) $customField['ID'],
                name: $customField['Name'] ?? '',
                type: $customField['Type'] ?? null,
                isMandatory: $customField['IsMandatory'] ?? null,
                listItems: $customField['ListItems'] ?? null,
                value: $data['Value'] ?? null,
            );
        }

        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? '',
            type: $data['Type'] ?? null,
            isMandatory: $data['IsMandatory'] ?? null,
            listItems: $data['ListItems'] ?? null,
            value: $data['Value'] ?? null,
        );
    }

    /**
     * Check if this field has a value set.
     */
    public function hasValue(): bool
    {
        return $this->value !== null && $this->value !== '';
    }

    /**
     * Check if this is a list-type custom field.
     */
    public function isListType(): bool
    {
        return $this->type === 'List' || ! empty($this->listItems);
    }
}
