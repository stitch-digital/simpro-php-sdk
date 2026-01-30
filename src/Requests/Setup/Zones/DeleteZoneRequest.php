<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Zones;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a zone.
 */
final class DeleteZoneRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $zoneId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/zones/{$this->zoneId}";
    }
}
