<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\Notes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Customers\Notes\CustomerNote;

final class GetCustomerNoteRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $customerId,
        private readonly int|string $noteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/{$this->customerId}/notes/{$this->noteId}";
    }

    public function createDtoFromResponse(Response $response): CustomerNote
    {
        return CustomerNote::fromResponse($response);
    }
}
