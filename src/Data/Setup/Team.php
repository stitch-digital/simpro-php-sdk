<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class Team
{
    /**
     * @param  array<TeamAvailability>|null  $availability
     * @param  array<Reference>|null  $costCenters
     * @param  array<TeamMember>|null  $members
     * @param  array<Reference>|null  $zones
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?array $availability = null,
        public ?array $costCenters = null,
        public ?array $members = null,
        public ?array $zones = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? '',
            availability: isset($data['Availability']) ? array_map(
                fn (array $item) => TeamAvailability::fromArray($item),
                $data['Availability']
            ) : null,
            costCenters: isset($data['CostCenters']) ? array_map(
                fn (array $item) => Reference::fromArray($item),
                $data['CostCenters']
            ) : null,
            members: isset($data['Members']) ? array_map(
                fn (array $item) => TeamMember::fromArray($item),
                $data['Members']
            ) : null,
            zones: isset($data['Zones']) ? array_map(
                fn (array $item) => Reference::fromArray($item),
                $data['Zones']
            ) : null,
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
