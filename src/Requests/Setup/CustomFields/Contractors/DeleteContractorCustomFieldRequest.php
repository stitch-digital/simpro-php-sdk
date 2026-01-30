<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Contractors;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;

/**
 * Delete a contractors custom field.
 */
final class DeleteContractorCustomFieldRequest extends AbstractDeleteCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'contractors';
    }
}
