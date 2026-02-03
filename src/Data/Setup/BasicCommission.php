<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

final readonly class BasicCommission
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type = 'Basic',
        public int $displayOrder = 0,
        public string $rule = 'Dollar',
        public float $rate = 0.0,
        public ?string $trigger = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? '',
            type: $data['Type'] ?? 'Basic',
            displayOrder: (int) ($data['DisplayOrder'] ?? 0),
            rule: $data['Rule'] ?? 'Dollar',
            rate: (float) ($data['Rate'] ?? 0.0),
            trigger: $data['Trigger'] ?? null,
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
