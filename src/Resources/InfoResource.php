<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Info\Info;
use Simpro\PhpSdk\Simpro\Requests\Info\InfoRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class InfoResource extends BaseResource
{
    /**
     * Get information about the Simpro instance.
     */
    public function info(): Info
    {
        $request = new InfoRequest;

        return $this->connector->send($request)->dto();
    }
}
