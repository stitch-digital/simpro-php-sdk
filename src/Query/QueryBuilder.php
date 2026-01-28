<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Query;

use Generator;
use Illuminate\Support\LazyCollection;
use InvalidArgumentException;
use Saloon\Http\Request;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Paginators\SimproPaginator;

/**
 * Fluent query builder for Simpro API requests.
 *
 * Allows chaining search criteria, ordering, and filters before execution.
 * Terminal methods (items(), collect(), first(), all()) trigger the API request.
 *
 * @example
 * $result = $connector->companies()->listDetailed()
 *     ->search(Search::make()->column('Name')->find('Test'))
 *     ->orderByDesc('Name')
 *     ->collect()
 *     ->first();
 *
 * @method int getTotalResults()
 */
final class QueryBuilder
{
    /** @var array<Search> */
    private array $searches = [];

    private SearchLogic $logic = SearchLogic::All;

    /** @var array<array{column: string, direction: SortDirection}> */
    private array $orderBy = [];

    /** @var array<string, string> */
    private array $filters = [];

    private ?SimproPaginator $paginator = null;

    public function __construct(
        private readonly AbstractSimproConnector $connector,
        private readonly Request $request,
    ) {}

    /**
     * Add search criteria.
     *
     * @param  Search|array<mixed>  $search  Single Search or array of Search objects
     */
    public function search(Search|array $search): self
    {
        if (is_array($search)) {
            foreach ($search as $s) {
                if (! $s instanceof Search) {
                    throw new InvalidArgumentException('Array must contain only Search objects');
                }
                $this->searches[] = $s;
            }
        } else {
            $this->searches[] = $search;
        }

        return $this;
    }

    /**
     * Add a search condition using operator syntax.
     *
     * @param  string  $column  Column name (supports dot notation)
     * @param  string  $operator  Operator: =, !=, <, <=, >, >=, like, in, not in, between
     * @param  mixed  $value  Value to compare (array for in/not in, [min, max] for between)
     */
    public function where(string $column, string $operator, mixed $value): self
    {
        $search = Search::make()->column($column);

        $search = match (strtolower($operator)) {
            '=', '==' => $search->equals($value),
            '!=' => $search->notEqual($value),
            '<' => $search->lessThan($value),
            '<=' => $search->lessThanOrEqual($value),
            '>' => $search->greaterThan($value),
            '>=' => $search->greaterThanOrEqual($value),
            'like' => $search->find($value),
            'starts with', 'startswith' => $search->startsWith($value),
            'ends with', 'endswith' => $search->endsWith($value),
            'in' => $search->in((array) $value),
            'not in', 'notin' => $search->notIn((array) $value),
            'between' => $search->between($value[0], $value[1]),
            default => throw new InvalidArgumentException("Unknown operator: {$operator}"),
        };

        $this->searches[] = $search;

        return $this;
    }

    /**
     * Set the search logic (how multiple criteria are combined).
     */
    public function logic(SearchLogic $logic): self
    {
        $this->logic = $logic;

        return $this;
    }

    /**
     * Use AND logic (all criteria must match).
     */
    public function matchAll(): self
    {
        $this->logic = SearchLogic::All;

        return $this;
    }

    /**
     * Use OR logic (any criterion can match).
     */
    public function matchAny(): self
    {
        $this->logic = SearchLogic::Any;

        return $this;
    }

    /**
     * Add ordering.
     *
     * @param  string  $column  Column to order by (accepts both camelCase and PascalCase)
     * @param  SortDirection|string  $direction  Sort direction
     */
    public function orderBy(string $column, SortDirection|string $direction = SortDirection::Ascending): self
    {
        if (is_string($direction)) {
            $direction = match (strtolower($direction)) {
                'asc', 'ascending' => SortDirection::Ascending,
                'desc', 'descending' => SortDirection::Descending,
                default => throw new InvalidArgumentException("Unknown sort direction: {$direction}"),
            };
        }

        $this->orderBy[] = ['column' => $this->normalizeFieldName($column), 'direction' => $direction];

        return $this;
    }

    /**
     * Normalize a field name to Simpro API format (PascalCase).
     * Accepts both camelCase (DTO style) and PascalCase (API style).
     *
     * Examples:
     *   'name' -> 'Name'
     *   'Name' -> 'Name'
     *   'id' -> 'ID'
     *   'dateIssued' -> 'DateIssued'
     */
    private function normalizeFieldName(string $column): string
    {
        // Special cases - common abbreviations that should be uppercase
        $specialCases = [
            'id' => 'ID',
            'uuid' => 'UUID',
            'ein' => 'EIN',
            'iban' => 'IBAN',
            'abn' => 'ABN',
            'acn' => 'ACN',
            'gst' => 'GST',
            'vat' => 'VAT',
            'url' => 'URL',
            'uri' => 'URI',
            'bsb' => 'BSB',
            'stc' => 'STC',
        ];

        // Split on dots for nested fields
        $parts = explode('.', $column);
        $normalized = [];

        foreach ($parts as $part) {
            $lowerPart = strtolower($part);
            if (isset($specialCases[$lowerPart])) {
                $normalized[] = $specialCases[$lowerPart];
            } else {
                // Convert to PascalCase (capitalize first letter)
                $normalized[] = ucfirst($part);
            }
        }

        return implode('.', $normalized);
    }

    /**
     * Order by column descending.
     */
    public function orderByDesc(string $column): self
    {
        return $this->orderBy($column, SortDirection::Descending);
    }

    /**
     * Order by column ascending (explicit).
     */
    public function orderByAsc(string $column): self
    {
        return $this->orderBy($column, SortDirection::Ascending);
    }

    /**
     * Add a raw filter parameter.
     */
    public function filter(string $key, string|int|float|bool $value): self
    {
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

        $this->filters[$key] = (string) $value;

        return $this;
    }

    /**
     * Get the underlying paginator.
     * Lazily creates the paginator on first access.
     */
    public function getPaginator(): SimproPaginator
    {
        if ($this->paginator === null) {
            $this->applyQueryParameters();
            $this->paginator = $this->connector->paginate($this->request);
        }

        return $this->paginator;
    }

    /**
     * Get an iterator over all items across all pages.
     *
     * @return Generator<mixed>
     */
    public function items(): Generator
    {
        return $this->getPaginator()->items();
    }

    /**
     * Get a lazy collection of all items.
     *
     * @return LazyCollection<int, mixed>
     */
    public function collect(): LazyCollection
    {
        return $this->getPaginator()->collect();
    }

    /**
     * Get the first item or null.
     */
    public function first(): mixed
    {
        $items = $this->items();

        return $items->current() ?: null;
    }

    /**
     * Get all items as an array.
     *
     * @return array<mixed>
     */
    public function all(): array
    {
        return iterator_to_array($this->items());
    }

    /**
     * Forward unknown method calls to the paginator.
     *
     * @param  array<mixed>  $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->getPaginator()->{$name}(...$arguments);
    }

    /**
     * Apply accumulated query parameters to the request.
     */
    private function applyQueryParameters(): void
    {
        // Apply search criteria
        foreach ($this->searches as $search) {
            [$column, $value] = $search->toQueryParam();
            $this->request->query()->add($column, $value);
        }

        // Apply search logic if we have multiple searches
        if (count($this->searches) > 1) {
            $this->request->query()->add('search', $this->logic->value);
        }

        // Apply ordering
        if (count($this->orderBy) > 0) {
            $orderParts = [];
            foreach ($this->orderBy as $order) {
                $prefix = $order['direction'] === SortDirection::Descending ? '-' : '';
                $orderParts[] = $prefix.$order['column'];
            }
            $this->request->query()->add('orderby', implode(',', $orderParts));
        }

        // Apply raw filters
        foreach ($this->filters as $key => $value) {
            $this->request->query()->add($key, $value);
        }
    }
}
