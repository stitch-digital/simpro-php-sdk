<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

final readonly class Currency
{
    public function __construct(
        public int $iD,
        public string $name,
        public string $code,
        public ?string $symbol,
        public ?float $rate,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            iD: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? '',
            code: $data['Code'] ?? '',
            symbol: $data['Symbol'] ?? null,
            rate: $data['Rate'] ?? null,
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
