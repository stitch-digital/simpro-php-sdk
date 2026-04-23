<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Sites\CustomFields;

use Saloon\Http\Response;

final readonly class SiteCustomFieldValue
{
    /**
     * @param  array<string>  $listItems
     */
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $type,
        public ?bool $isMandatory,
        public ?array $listItems,
        public mixed $value,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? null,
            type: $data['Type'] ?? null,
            isMandatory: $data['IsMandatory'] ?? null,
            listItems: $data['ListItems'] ?? [],
            value: $data['Value'] ?? null,
        );
    }
}
