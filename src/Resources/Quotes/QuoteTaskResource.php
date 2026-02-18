<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Quotes\Tasks\QuoteTask;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Tasks\GetQuoteTaskRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Tasks\ListQuoteTasksRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class QuoteTaskResource extends BaseResource
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
        $request = new ListQuoteTasksRequest($this->companyId, $this->quoteId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    public function get(int|string $taskId): QuoteTask
    {
        $request = new GetQuoteTaskRequest($this->companyId, $this->quoteId, $taskId);

        return $this->connector->send($request)->dto();
    }
}
