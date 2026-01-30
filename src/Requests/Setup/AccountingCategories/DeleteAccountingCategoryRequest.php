<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\AccountingCategories;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete an accounting category.
 */
final class DeleteAccountingCategoryRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $accCategoryId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/accCategories/{$this->accCategoryId}";
    }
}
