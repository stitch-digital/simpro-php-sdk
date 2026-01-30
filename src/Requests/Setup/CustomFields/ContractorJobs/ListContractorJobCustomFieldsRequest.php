<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\ContractorJobs;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListCustomFieldsRequest;

/**
 * List all contractorJobs custom fields.
 */
final class ListContractorJobCustomFieldsRequest extends AbstractListCustomFieldsRequest
{
    protected function getResourcePath(): string
    {
        return 'contractorJobs';
    }
}
