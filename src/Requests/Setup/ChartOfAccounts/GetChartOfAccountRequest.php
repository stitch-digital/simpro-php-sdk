<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\ChartOfAccounts;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\ChartOfAccount;

/**
 * Retrieve details for a specific chart of account.
 */
final class GetChartOfAccountRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $accountId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/chartOfAccounts/{$this->accountId}";
    }

    public function createDtoFromResponse(Response $response): ChartOfAccount
    {
        return ChartOfAccount::fromResponse($response);
    }
}
