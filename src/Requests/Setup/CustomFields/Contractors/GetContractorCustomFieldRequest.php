<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Contractors;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractGetCustomFieldRequest;

/**
 * Get a specific contractors custom field.
 */
final class GetContractorCustomFieldRequest extends AbstractGetCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'contractors';
    }
}
