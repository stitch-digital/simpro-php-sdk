<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\PaymentTerms;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\PaymentTerm;

/**
 * List all payment terms.
 */
final class ListPaymentTermsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/paymentTerms/";
    }

    /**
     * @return array<PaymentTerm>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => PaymentTerm::fromArray($item),
            $data
        );
    }
}
