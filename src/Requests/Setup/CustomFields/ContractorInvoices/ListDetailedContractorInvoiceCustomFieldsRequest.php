<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\ContractorInvoices;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractListDetailedCustomFieldsRequest;

/**
 * List all contractor invoice custom fields with full details.
 */
final class ListDetailedContractorInvoiceCustomFieldsRequest extends AbstractListDetailedCustomFieldsRequest
{
    protected function getResourcePath(): string
    {
        return 'contractorInvoices';
    }
}
