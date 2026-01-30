<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\ContractorInvoices;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;

/**
 * Get a specific contractorInvoices custom field.
 */
final class GetContractorInvoiceCustomFieldRequest extends AbstractGetCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'contractorInvoices';
    }
}
