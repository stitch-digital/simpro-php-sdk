<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CustomFields;

use Saloon\Http\Response;

final readonly class JobCustomFieldValue
{
    /**
     * @param  array<string>|null  $listItems
     */
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $type,
        public mixed $value,
        public ?bool $isMandatory,
        public ?array $listItems,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    public static function fromArray(array $data): self
    {
        // Handle nested CustomField structure
        $customField = $data['CustomField'] ?? $data;

        return new self(
            id: $customField['ID'] ?? $data['ID'],
            name: $customField['Name'] ?? $data['Name'] ?? null,
            type: $customField['Type'] ?? $data['Type'] ?? null,
            value: $data['Value'] ?? null,
            isMandatory: $customField['IsMandatory'] ?? $data['IsMandatory'] ?? null,
            listItems: $customField['ListItems'] ?? $data['ListItems'] ?? null,
        );
    }
}
