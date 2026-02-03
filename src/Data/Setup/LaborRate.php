<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\TaxCode;

/**
 * LaborRate DTO.
 */
final readonly class LaborRate
{
    public function __construct(
        public int $id,
        public string $name,
        public float $costRate = 0.0,
        public float $markup = 0.0,
        public float $multiplier = 1.0,
        public ?TaxCode $taxCode = null,
        public bool $isDefault = false,
        public bool $addToAllCustomers = false,
        public bool $incOverhead = false,
        public ?Reference $plant = null,
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
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? '',
            costRate: (float) ($data['CostRate'] ?? 0.0),
            markup: (float) ($data['Markup'] ?? 0.0),
            multiplier: (float) ($data['Multiplier'] ?? 1.0),
            taxCode: isset($data['TaxCode']) ? TaxCode::fromArray($data['TaxCode']) : null,
            isDefault: (bool) ($data['IsDefault'] ?? false),
            addToAllCustomers: (bool) ($data['AddToAllCustomers'] ?? false),
            incOverhead: (bool) ($data['IncOverhead'] ?? false),
            plant: isset($data['Plant']) ? Reference::fromArray($data['Plant']) : null,
            archived: (bool) ($data['Archived'] ?? false),
        );
    }
}
