<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\ChartOfAccounts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a chart of account.
 */
final class DeleteChartOfAccountRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $accountId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/chartOfAccounts/{$this->accountId}";
    }
}
