<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Quotes\Notes\QuoteNote;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Notes\CreateQuoteNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Notes\DeleteQuoteNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Notes\GetQuoteNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Notes\ListQuoteNotesRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Notes\UpdateQuoteNoteRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class QuoteNoteResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $quoteId,
    ) {
        parent::__construct($connector);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListQuoteNotesRequest($this->companyId, $this->quoteId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    public function get(int|string $noteId): QuoteNote
    {
        $request = new GetQuoteNoteRequest($this->companyId, $this->quoteId, $noteId);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateQuoteNoteRequest($this->companyId, $this->quoteId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $noteId, array $data): Response
    {
        $request = new UpdateQuoteNoteRequest($this->companyId, $this->quoteId, $noteId, $data);

        return $this->connector->send($request);
    }

    public function delete(int|string $noteId): Response
    {
        $request = new DeleteQuoteNoteRequest($this->companyId, $this->quoteId, $noteId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple quote notes in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/notes",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple quote notes in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/notes",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple quote notes in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/notes",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
