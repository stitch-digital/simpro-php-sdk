<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\PaymentMethods;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\PaymentMethod;

/**
 * Retrieve details for a specific payment method.
 */
final class GetPaymentMethodRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $paymentMethodId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/paymentMethods/{$this->paymentMethodId}";
    }

    public function createDtoFromResponse(Response $response): PaymentMethod
    {
        return PaymentMethod::fromResponse($response);
    }
}
