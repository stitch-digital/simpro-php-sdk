<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\PaymentTerms;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a payment term.
 */
final class DeletePaymentTermRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $paymentTermId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/paymentTerms/{$this->paymentTermId}";
    }
}
