<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Webhooks;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a webhook subscription.
 */
final class DeleteWebhookRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $webhookId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/webhooks/{$this->webhookId}";
    }
}
