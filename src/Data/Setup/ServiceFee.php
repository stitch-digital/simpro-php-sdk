<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\TaxCode;

/**
 * ServiceFee DTO.
 */
final readonly class ServiceFee
{
    public function __construct(
        public int $id,
        public string $name,
        public ?TaxCode $salesTaxCode = null,
        public ?float $laborTime = null,
        public float $price = 0.0,
        public int $displayOrder = 0,
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
            salesTaxCode: ! empty($data['SalesTaxCode']) ? TaxCode::fromArray($data['SalesTaxCode']) : null,
            laborTime: isset($data['LaborTime']) ? (float) $data['LaborTime'] : null,
            price: (float) ($data['Price'] ?? 0.0),
            displayOrder: (int) ($data['DisplayOrder'] ?? 0),
            archived: (bool) ($data['Archived'] ?? false),
        );
    }
}
