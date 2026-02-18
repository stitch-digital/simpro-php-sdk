<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Quotes\Notes\QuoteNote;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
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
}
