<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Setup;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomerTagResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\ProjectTagResource;

/**
 * Scope for navigating tag resources.
 */
final class TagScope
{
    public function __construct(
        private readonly AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {}

    /**
     * Access customer tag endpoints.
     */
    public function customers(): CustomerTagResource
    {
        return new CustomerTagResource($this->connector, $this->companyId);
    }

    /**
     * Access project tag endpoints.
     */
    public function projects(): ProjectTagResource
    {
        return new ProjectTagResource($this->connector, $this->companyId);
    }
}
