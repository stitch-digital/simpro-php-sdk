<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\ArchiveReasons\Quotes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteQuoteArchiveReasonRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $archiveReasonId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/archiveReasons/quotes/{$this->archiveReasonId}";
    }
}
