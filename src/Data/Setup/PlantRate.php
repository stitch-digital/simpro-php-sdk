<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\TaxCode;

/**
 * PlantRate DTO.
 */
final readonly class PlantRate
{
    public function __construct(
        public int $id,
        public string $name,
        public float $costRate = 0.0,
        public float $markup = 0.0,
        public ?TaxCode $taxCode = null,
        public bool $addToAllCustomers = false,
        public ?Reference $plant = null,
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
            costRate: (float) ($data['CostRate'] ?? 0.0),
            markup: (float) ($data['Markup'] ?? 0.0),
            taxCode: isset($data['TaxCode']) ? TaxCode::fromArray($data['TaxCode']) : null,
            addToAllCustomers: (bool) ($data['AddToAllCustomers'] ?? false),
            plant: isset($data['Plant']) ? Reference::fromArray($data['Plant']) : null,
        );
    }
}
