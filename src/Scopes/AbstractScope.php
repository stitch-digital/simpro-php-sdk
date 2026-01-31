<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;

/**
 * Base scope class for navigating nested API resources.
 *
 * Scopes capture parent context (company ID, job ID, etc.) and provide
 * navigation methods to child resources. They are lightweight objects
 * that only hold IDs and a connector reference.
 */
abstract class AbstractScope
{
    public function __construct(
        protected readonly AbstractSimproConnector $connector,
        protected readonly int $companyId,
    ) {}
}
