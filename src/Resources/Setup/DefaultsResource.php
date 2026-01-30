<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults;
use Simpro\PhpSdk\Simpro\Requests\Setup\Defaults\GetDefaultsRequest;

/**
 * Resource for getting company defaults.
 *
 * @property AbstractSimproConnector $connector
 */
final class DefaultsResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * Get company defaults.
     */
    public function get(): Defaults
    {
        $request = new GetDefaultsRequest($this->companyId);

        return $this->connector->send($request)->dto();
    }
}
