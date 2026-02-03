<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

final readonly class AdvancedCommission
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type = 'Advanced',
        public int $displayOrder = 0,
        public ?AdvancedCommissionComponents $components = null,
        public string $trigger = 'CostCenterLocked',
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? '',
            type: $data['Type'] ?? 'Advanced',
            displayOrder: (int) ($data['DisplayOrder'] ?? 0),
            components: isset($data['Components']) ? AdvancedCommissionComponents::fromArray($data['Components']) : null,
            trigger: $data['Trigger'] ?? 'CostCenterLocked',
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
