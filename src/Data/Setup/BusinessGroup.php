<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * Business group DTO.
 */
final readonly class BusinessGroup
{
    /**
     * @param  array<Reference>  $costCenters
     */
    public function __construct(
        public int $id,
        public string $name,
        public array $costCenters = [],
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
        $costCenters = [];
        if (! empty($data['CostCenters']) && is_array($data['CostCenters'])) {
            $costCenters = array_map(
                fn (array $item) => Reference::fromArray($item),
                $data['CostCenters']
            );
        }

        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? '',
            costCenters: $costCenters,
        );
    }
}
