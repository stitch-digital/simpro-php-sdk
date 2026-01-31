<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Employees;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Employees\EmployeeAttachmentFileResource;
use Simpro\PhpSdk\Simpro\Resources\Employees\EmployeeAttachmentFolderResource;
use Simpro\PhpSdk\Simpro\Resources\Employees\EmployeeCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Employees\EmployeeLicenceResource;
use Simpro\PhpSdk\Simpro\Resources\Employees\EmployeeTimesheetResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific employee, providing access to nested resources.
 *
 * @example
 * // Access employee timesheets
 * $connector->employees(companyId: 0)->employee(employeeId: 123)->timesheets()->list();
 *
 * // Access employee custom fields
 * $connector->employees(companyId: 0)->employee(employeeId: 123)->customFields()->list();
 *
 * // Navigate to a specific licence
 * $connector->employees(companyId: 0)->employee(employeeId: 123)->licence(licenceId: 456)->attachmentFiles()->list();
 */
final class EmployeeScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $employeeId,
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
     * Access timesheets for this employee.
     */
    public function timesheets(): EmployeeTimesheetResource
    {
        return new EmployeeTimesheetResource($this->connector, $this->companyId, $this->employeeId);
    }

    /**
     * Access custom fields for this employee.
     */
    public function customFields(): EmployeeCustomFieldResource
    {
        return new EmployeeCustomFieldResource($this->connector, $this->companyId, $this->employeeId);
    }

    /**
     * Access attachment files for this employee.
     */
    public function attachmentFiles(): EmployeeAttachmentFileResource
    {
        return new EmployeeAttachmentFileResource($this->connector, $this->companyId, $this->employeeId);
    }

    /**
     * Access attachment folders for this employee.
     */
    public function attachmentFolders(): EmployeeAttachmentFolderResource
    {
        return new EmployeeAttachmentFolderResource($this->connector, $this->companyId, $this->employeeId);
    }

    /**
     * Access licences for this employee.
     */
    public function licences(): EmployeeLicenceResource
    {
        return new EmployeeLicenceResource($this->connector, $this->companyId, $this->employeeId);
    }

    /**
     * Navigate to a specific licence scope for nested resources.
     *
     * @example
     * // Access licence attachment files
     * $connector->employees(companyId: 0)->employee(employeeId: 123)->licence(licenceId: 456)->attachmentFiles()->list();
     */
    public function licence(int|string $licenceId): LicenceScope
    {
        return new LicenceScope($this->connector, $this->companyId, $this->employeeId, $licenceId);
    }
}
