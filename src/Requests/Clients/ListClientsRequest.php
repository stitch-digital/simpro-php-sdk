<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Clients;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\Traits\Plugins\AcceptsJson;
use Simpro\PhpSdk\Simpro\Data\Clients\ClientListResponse;

final class ListClientsRequest extends Request implements Paginatable
{
    use AcceptsJson;

    /**
     * Define the method that the request will use.
     */
    protected Method $method = Method::GET;

    /**
     * Constructor
     */
    public function __construct(
        private ?int $page = null,
        private ?int $perPage = null
    ) {
        //
    }

    /**
     * Define the endpoint for the request.
     */
    public function resolveEndpoint(): string
    {
        return '/api/v2/clients/';
    }

    /**
     * Create a DTO from the response.
     */
    public function createDtoFromResponse(Response $response): ClientListResponse
    {
        return ClientListResponse::fromArray($response->json());
    }

    /**
     * Register the default query parameters.
     *
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        $query = [];

        if ($this->perPage !== null) {
            $query['page[limit]'] = $this->perPage;
        }

        if ($this->page !== null && $this->perPage !== null) {
            $query['page[offset]'] = ($this->page - 1) * $this->perPage;
        }

        return $query;
    }
}
