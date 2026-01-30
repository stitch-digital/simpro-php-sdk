<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\Webhook;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Webhooks\CreateWebhookRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Webhooks\DeleteWebhookRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Webhooks\GetWebhookRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Webhooks\ListWebhooksRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Webhooks\UpdateWebhookRequest;

/**
 * Resource for managing webhook subscriptions.
 *
 * @property AbstractSimproConnector $connector
 */
final class WebhookResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all webhook subscriptions.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListWebhooksRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific webhook subscription.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $webhookId, ?array $columns = null): Webhook
    {
        $request = new GetWebhookRequest($this->companyId, $webhookId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new webhook subscription.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateWebhookRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a webhook subscription.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $webhookId, array $data): Response
    {
        $request = new UpdateWebhookRequest($this->companyId, $webhookId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a webhook subscription.
     */
    public function delete(int|string $webhookId): Response
    {
        $request = new DeleteWebhookRequest($this->companyId, $webhookId);

        return $this->connector->send($request);
    }
}
