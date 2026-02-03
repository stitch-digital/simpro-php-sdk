<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Webhooks;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\Webhook;

/**
 * List all webhook subscriptions with full details.
 */
final class ListDetailedWebhooksRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for webhooks.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'CallbackURL',
        'Secret',
        'Email',
        'Description',
        'Events',
        'Status',
        'DateCreated',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/webhooks/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', self::DETAILED_COLUMNS),
        ];
    }

    /**
     * @return array<Webhook>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item): Webhook => Webhook::fromArray($item),
            $data
        );
    }
}
