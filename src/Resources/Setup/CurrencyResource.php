<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\Currency;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Currencies\GetCurrencyRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Currencies\ListCurrenciesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Currencies\UpdateCurrencyRequest;

/**
 * Resource for managing Currencys.
 *
 * @property AbstractSimproConnector $connector
 */
final class CurrencyResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCurrenciesRequest($this->companyId);

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
    public function get(int|string $currencyId, ?array $columns = null): Currency
    {
        $request = new GetCurrencyRequest($this->companyId, $currencyId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $currencyId, array $data): Response
    {
        $request = new UpdateCurrencyRequest($this->companyId, $currencyId, $data);

        return $this->connector->send($request);
    }
}
