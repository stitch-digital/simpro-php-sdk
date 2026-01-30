<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\PaymentTerms;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\PaymentTerm;

/**
 * Retrieve details for a specific payment term.
 */
final class GetPaymentTermRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $paymentTermId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/paymentTerms/{$this->paymentTermId}";
    }

    public function createDtoFromResponse(Response $response): PaymentTerm
    {
        return PaymentTerm::fromResponse($response);
    }
}
