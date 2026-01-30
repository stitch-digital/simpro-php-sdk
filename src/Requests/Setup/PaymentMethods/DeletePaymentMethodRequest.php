<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\PaymentMethods;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a payment method.
 */
final class DeletePaymentMethodRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $paymentMethodId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/paymentMethods/{$this->paymentMethodId}";
    }
}
