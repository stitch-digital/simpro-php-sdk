<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * Custom field DTO.
 */
final readonly class CustomField
{
    /**
     * @param  array<string>  $options
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $type = null,
        public ?string $customFieldType = null,
        public bool $required = false,
        public bool $archived = false,
        public array $options = [],
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
            type: $data['Type'] ?? null,
            customFieldType: $data['CustomFieldType'] ?? null,
            required: (bool) ($data['Required'] ?? false),
            archived: (bool) ($data['Archived'] ?? false),
            options: $data['Options'] ?? [],
        );
    }
}
