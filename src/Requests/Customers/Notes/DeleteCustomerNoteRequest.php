<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\Notes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteCustomerNoteRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $customerId,
        private readonly int|string $noteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/{$this->customerId}/notes/{$this->noteId}";
    }
}
