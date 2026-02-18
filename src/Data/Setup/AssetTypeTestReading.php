<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * AssetTypeTestReading DTO (detail response).
 */
final readonly class AssetTypeTestReading
{
    /**
     * @param  array<string>|null  $listItems
     * @param  array<AssetTypeServiceLevelListItem>  $serviceLevels
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $type = 'Text',
        public ?array $listItems = null,
        public bool $isMandatory = false,
        public array $serviceLevels = [],
        public int $order = 0,
        public bool $archived = false,
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
        $serviceLevels = [];
        if (! empty($data['ServiceLevels']) && is_array($data['ServiceLevels'])) {
            foreach ($data['ServiceLevels'] as $serviceLevel) {
                $serviceLevels[] = AssetTypeServiceLevelListItem::fromArray($serviceLevel);
            }
        }

        return new self(
            id: (int) $data['ID'],
            name: $data['Name'],
            type: $data['Type'] ?? 'Text',
            listItems: $data['ListItems'] ?? null,
            isMandatory: (bool) ($data['IsMandatory'] ?? false),
            serviceLevels: $serviceLevels,
            order: (int) ($data['Order'] ?? 0),
            archived: (bool) ($data['Archived'] ?? false),
        );
    }
}
