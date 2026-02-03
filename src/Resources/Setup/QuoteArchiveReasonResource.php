<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\QuoteArchiveReason;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\ArchiveReasons\Quotes\CreateQuoteArchiveReasonRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ArchiveReasons\Quotes\DeleteQuoteArchiveReasonRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ArchiveReasons\Quotes\GetQuoteArchiveReasonRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ArchiveReasons\Quotes\ListDetailedQuoteArchiveReasonsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ArchiveReasons\Quotes\ListQuoteArchiveReasonsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ArchiveReasons\Quotes\UpdateQuoteArchiveReasonRequest;

/**
 * Resource for managing QuoteArchiveReasons.
 *
 * @property AbstractSimproConnector $connector
 */
final class QuoteArchiveReasonResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all quote archive reasons with minimal fields (ID, ArchiveReason).
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListQuoteArchiveReasonsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all quote archive reasons with full details.
     *
     * Returns QuoteArchiveReason DTOs with all fields (ID, ArchiveReason, DisplayOrder, Archived).
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedQuoteArchiveReasonsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific item.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $archiveReasonId, ?array $columns = null): QuoteArchiveReason
    {
        $request = new GetQuoteArchiveReasonRequest($this->companyId, $archiveReasonId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new item.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateQuoteArchiveReasonRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $archiveReasonId, array $data): Response
    {
        $request = new UpdateQuoteArchiveReasonRequest($this->companyId, $archiveReasonId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $archiveReasonId): Response
    {
        $request = new DeleteQuoteArchiveReasonRequest($this->companyId, $archiveReasonId);

        return $this->connector->send($request);
    }
}
