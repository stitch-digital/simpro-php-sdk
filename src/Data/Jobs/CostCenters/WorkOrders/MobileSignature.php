<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders;

use Saloon\Http\Response;

final readonly class MobileSignature
{
    public function __construct(
        public ?string $technician,
        public ?string $client,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            technician: $data['Technician'] ?? null,
            client: $data['Client'] ?? null,
        );
    }
}
