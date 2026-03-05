<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Contractors;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Contractors\ContractorAttachmentFileResource;
use Simpro\PhpSdk\Simpro\Resources\Contractors\ContractorAttachmentFolderResource;
use Simpro\PhpSdk\Simpro\Resources\Contractors\ContractorCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Contractors\ContractorLicenceResource;
use Simpro\PhpSdk\Simpro\Resources\Contractors\ContractorTimesheetResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific contractor, providing access to nested resources.
 *
 * @example
 * // Access contractor timesheets
 * $connector->contractors(companyId: 0)->contractor(contractorId: 123)->timesheets()->list();
 *
 * // Access contractor custom fields
 * $connector->contractors(companyId: 0)->contractor(contractorId: 123)->customFields()->list();
 *
 * // Navigate to a specific licence
 * $connector->contractors(companyId: 0)->contractor(contractorId: 123)->licence(licenceId: 456)->attachmentFiles()->list();
 */
final class ContractorScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $contractorId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Get the contractor ID for this scope.
     */
    public function getContractorId(): int|string
    {
        return $this->contractorId;
    }

    /**
     * Access timesheets for this contractor.
     */
    public function timesheets(): ContractorTimesheetResource
    {
        return new ContractorTimesheetResource($this->connector, $this->companyId, $this->contractorId);
    }

    /**
     * Access custom fields for this contractor.
     */
    public function customFields(): ContractorCustomFieldResource
    {
        return new ContractorCustomFieldResource($this->connector, $this->companyId, $this->contractorId);
    }

    /**
     * Access attachment files for this contractor.
     */
    public function attachmentFiles(): ContractorAttachmentFileResource
    {
        return new ContractorAttachmentFileResource($this->connector, $this->companyId, $this->contractorId);
    }

    /**
     * Access attachment folders for this contractor.
     */
    public function attachmentFolders(): ContractorAttachmentFolderResource
    {
        return new ContractorAttachmentFolderResource($this->connector, $this->companyId, $this->contractorId);
    }

    /**
     * Access licences for this contractor.
     */
    public function licences(): ContractorLicenceResource
    {
        return new ContractorLicenceResource($this->connector, $this->companyId, $this->contractorId);
    }

    /**
     * Navigate to a specific licence scope for nested resources.
     *
     * @example
     * // Access licence attachment files
     * $connector->contractors(companyId: 0)->contractor(contractorId: 123)->licence(licenceId: 456)->attachmentFiles()->list();
     */
    public function licence(int|string $licenceId): LicenceScope
    {
        return new LicenceScope($this->connector, $this->companyId, $this->contractorId, $licenceId);
    }
}
