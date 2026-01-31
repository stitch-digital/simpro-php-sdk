<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Quotes\Quote;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CreateQuoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\DeleteQuoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\GetQuoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\ListQuotesRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\UpdateQuoteRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class QuoteResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all quotes.
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListQuotesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific quote.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $quoteId, ?array $columns = null): Quote
    {
        $request = new GetQuoteRequest($this->companyId, $quoteId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new quote.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created quote
     */
    public function create(array $data): int
    {
        $request = new CreateQuoteRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing quote.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $quoteId, array $data): Response
    {
        $request = new UpdateQuoteRequest($this->companyId, $quoteId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a quote.
     */
    public function delete(int|string $quoteId): Response
    {
        $request = new DeleteQuoteRequest($this->companyId, $quoteId);

        return $this->connector->send($request);
    }
}
