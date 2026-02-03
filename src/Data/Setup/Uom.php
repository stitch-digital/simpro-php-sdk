<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * Uom (Unit of Measurement) DTO.
 */
final readonly class Uom
{
    public function __construct(
        public int $id,
        public string $name,
        public bool $wholeNoOnly = false,
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
            wholeNoOnly: (bool) ($data['WholeNoOnly'] ?? false),
        );
    }
}
