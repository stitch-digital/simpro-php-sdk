<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * Labor rate overhead settings.
 */
final readonly class Overhead
{
    public function __construct(
        public float $rate,
        public ?string $calculationType = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            rate: (float) ($data['Rate'] ?? 0),
            calculationType: $data['CalculationType'] ?? null,
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
