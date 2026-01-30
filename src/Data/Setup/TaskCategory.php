<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

final readonly class TaskCategory
{
    public function __construct(
        public int $iD,
        public string $name,
        public ?string $color,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            iD: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? '',
            color: $data['Color'] ?? null,
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
