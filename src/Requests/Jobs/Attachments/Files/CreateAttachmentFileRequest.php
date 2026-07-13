<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Jobs\Attachments\Files;

use RuntimeException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class CreateAttachmentFileRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        private readonly int $companyId,
        private readonly int|string $jobId,
        private readonly array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/attachments/files/";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }

    public function createDtoFromResponse(Response $response): string
    {
        $data = $response->json();
        $id = $data['ID'] ?? null;

        if (! is_int($id) && ! is_string($id)) {
            throw new RuntimeException(sprintf(
                'Simpro create-attachment response missing ID (got %s)',
                json_encode($data),
            ));
        }

        $id = (string) $id;

        if ($id === '' || $id === '0') {
            throw new RuntimeException(sprintf(
                'Simpro create-attachment response returned an empty/zero ID (got %s)',
                json_encode($data),
            ));
        }

        return $id;
    }
}
