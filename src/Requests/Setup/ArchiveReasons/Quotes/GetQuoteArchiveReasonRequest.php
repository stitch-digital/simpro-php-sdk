<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\ArchiveReasons\Quotes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\QuoteArchiveReason;

final class GetQuoteArchiveReasonRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $archiveReasonId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/archiveReasons/quotes/{$this->archiveReasonId}";
    }

    public function createDtoFromResponse(Response $response): QuoteArchiveReason
    {
        return QuoteArchiveReason::fromResponse($response);
    }
}
