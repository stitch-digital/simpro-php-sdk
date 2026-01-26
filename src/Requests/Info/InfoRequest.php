<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Info;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Info\Info;

final class InfoRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/api/v1.0/info/';
    }

    public function createDtoFromResponse(Response $response): Info
    {
        return Info::fromResponse($response);
    }
}
