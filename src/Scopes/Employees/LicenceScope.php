<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Employees;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Employees\LicenceAttachmentFileResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific employee licence, providing access to nested resources.
 *
 * @example
 * // Access licence attachment files
 * $connector->employees(companyId: 0)->employee(employeeId: 123)->licence(licenceId: 456)->attachmentFiles()->list();
 */
final class LicenceScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int|string $companyId,
        private readonly int|string $employeeId,
        private readonly int|string $licenceId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Get the employee ID for this scope.
     */
    public function getEmployeeId(): int|string
    {
        return $this->employeeId;
    }

    /**
     * Get the licence ID for this scope.
     */
    public function getLicenceId(): int|string
    {
        return $this->licenceId;
    }

    /**
     * Access attachment files for this licence.
     */
    public function attachmentFiles(): LicenceAttachmentFileResource
    {
        return new LicenceAttachmentFileResource($this->connector, $this->companyId, $this->employeeId, $this->licenceId);
    }
}
