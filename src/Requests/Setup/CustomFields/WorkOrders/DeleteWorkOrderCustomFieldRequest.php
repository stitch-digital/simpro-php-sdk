<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\WorkOrders;

use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\AbstractDeleteCustomFieldRequest;

/**
 * Delete a workOrders custom field.
 */
final class DeleteWorkOrderCustomFieldRequest extends AbstractDeleteCustomFieldRequest
{
    protected function getResourcePath(): string
    {
        return 'workOrders';
    }
}
