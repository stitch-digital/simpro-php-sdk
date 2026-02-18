<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class SecurityGroup
{
    /**
     * @param  array<Reference>  $dashboards
     */
    public function __construct(
        public int $id,
        public string $name,
        public array $dashboards,
        public ?Reference $businessGroup,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? '',
            dashboards: array_map(
                fn (array $item): Reference => Reference::fromArray($item),
                $data['Dashboards'] ?? []
            ),
            businessGroup: ! empty($data['BusinessGroup'])
                ? Reference::fromArray($data['BusinessGroup'])
                : null,
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
