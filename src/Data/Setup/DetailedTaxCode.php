<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * Detailed tax code DTO with all available fields.
 */
final readonly class DetailedTaxCode
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public float $rate,
        public bool $reverseTaxEnabled = false,
        public bool $isPartIncomeDefault = false,
        public bool $isLaborIncomeDefault = false,
        public bool $archived = false,
        public ?string $dateModified = null,
        public ?string $href = null,
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
            code: $data['Code'] ?? '',
            name: $data['Name'] ?? '',
            rate: (float) ($data['Rate'] ?? 0),
            reverseTaxEnabled: (bool) ($data['ReverseTaxEnabled'] ?? false),
            isPartIncomeDefault: (bool) ($data['IsPartIncomeDefault'] ?? false),
            isLaborIncomeDefault: (bool) ($data['IsLaborIncomeDefault'] ?? false),
            archived: (bool) ($data['Archived'] ?? false),
            dateModified: $data['DateModified'] ?? null,
            href: $data['_href'] ?? null,
        );
    }
}
