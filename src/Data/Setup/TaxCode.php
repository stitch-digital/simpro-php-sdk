<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * Tax code DTO.
 */
final readonly class TaxCode
{
    public function __construct(
        public int $id,
        public string $code,
        public string $type,
        public float $rate,
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
            type: $data['Type'] ?? '',
            rate: (float) ($data['Rate'] ?? 0),
            href: $data['_href'] ?? null,
        );
    }
}
