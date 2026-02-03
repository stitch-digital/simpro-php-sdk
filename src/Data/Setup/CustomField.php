<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * Custom field DTO (setup).
 */
final readonly class CustomField
{
    /**
     * @param  array<string>|null  $listItems
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $type = 'Text',
        public ?array $listItems = null,
        public bool $isMandatory = false,
        public int $order = 0,
        public bool $archived = false,
        public bool $locked = false,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? '',
            type: $data['Type'] ?? 'Text',
            listItems: $data['ListItems'] ?? null,
            isMandatory: (bool) ($data['IsMandatory'] ?? false),
            order: (int) ($data['Order'] ?? 0),
            archived: (bool) ($data['Archived'] ?? false),
            locked: (bool) ($data['Locked'] ?? false),
        );
    }
}
