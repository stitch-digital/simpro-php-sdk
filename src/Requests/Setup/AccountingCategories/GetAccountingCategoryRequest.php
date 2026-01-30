<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\AccountingCategories;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\AccountingCategory;

/**
 * Retrieve details for a specific accounting category.
 */
final class GetAccountingCategoryRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $accCategoryId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/accCategories/{$this->accCategoryId}";
    }

    public function createDtoFromResponse(Response $response): AccountingCategory
    {
        return AccountingCategory::fromResponse($response);
    }
}
