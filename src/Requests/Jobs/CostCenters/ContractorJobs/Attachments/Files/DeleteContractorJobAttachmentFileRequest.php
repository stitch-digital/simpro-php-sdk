<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\Attachments\Files;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteContractorJobAttachmentFileRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $contractorJobId,
        private readonly int|string $fileId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/contractorJobs/{$this->contractorJobId}/attachments/files/{$this->fileId}";
    }
}
