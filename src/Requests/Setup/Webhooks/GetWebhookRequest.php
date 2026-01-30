<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Webhooks;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\Webhook;

/**
 * Retrieve details for a specific webhook subscription.
 */
final class GetWebhookRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $webhookId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/webhooks/{$this->webhookId}";
    }

    public function createDtoFromResponse(Response $response): Webhook
    {
        return Webhook::fromResponse($response);
    }
}
