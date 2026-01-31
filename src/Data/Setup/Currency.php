<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

final readonly class Currency
{
    public function __construct(
        public string $id,
        public string $name,
        public string $symbol,
        public float $exchangeRate,
        public bool $visible,
        public string $defaultSymbol,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['ID'] ?? ''),
            name: (string) ($data['Name'] ?? ''),
            symbol: (string) ($data['Symbol'] ?? ''),
            exchangeRate: (float) ($data['ExchangeRate'] ?? 0),
            visible: (bool) ($data['Visible'] ?? false),
            defaultSymbol: (string) ($data['DefaultSymbol'] ?? ''),
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
