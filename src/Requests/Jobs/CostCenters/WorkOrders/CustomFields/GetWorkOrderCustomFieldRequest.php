<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\CustomFields;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Jobs\CustomFields\JobCustomFieldValue;

final class GetWorkOrderCustomFieldRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int $jobId,
        private readonly int $sectionId,
        private readonly int $costCenterId,
        private readonly int $workOrderId,
        private readonly int $customFieldId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/workOrders/{$this->workOrderId}/customFields/{$this->customFieldId}";
    }

    public function createDtoFromResponse(Response $response): JobCustomFieldValue
    {
        return JobCustomFieldValue::fromResponse($response);
    }
}
