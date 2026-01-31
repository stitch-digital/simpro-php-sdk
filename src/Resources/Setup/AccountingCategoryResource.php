<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\AccountingCategory;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\AccountingCategories\CreateAccountingCategoryRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AccountingCategories\DeleteAccountingCategoryRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AccountingCategories\GetAccountingCategoryRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AccountingCategories\ListAccountingCategoriesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AccountingCategories\UpdateAccountingCategoryRequest;

/**
 * Resource for managing accounting categories.
 *
 * @property AbstractSimproConnector $connector
 */
final class AccountingCategoryResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all accounting categories.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListAccountingCategoriesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific accounting category.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $categoryId, ?array $columns = null): AccountingCategory
    {
        $request = new GetAccountingCategoryRequest($this->companyId, $categoryId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new accounting category.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateAccountingCategoryRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an accounting category.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $categoryId, array $data): Response
    {
        $request = new UpdateAccountingCategoryRequest($this->companyId, $categoryId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an accounting category.
     */
    public function delete(int|string $categoryId): Response
    {
        $request = new DeleteAccountingCategoryRequest($this->companyId, $categoryId);

        return $this->connector->send($request);
    }
}
