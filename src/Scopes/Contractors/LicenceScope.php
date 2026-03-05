<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Contractors;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Contractors\LicenceAttachmentFileResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific contractor licence, providing access to nested resources.
 *
 * @example
 * // Access licence attachment files
 * $connector->contractors(companyId: 0)->contractor(contractorId: 123)->licence(licenceId: 456)->attachmentFiles()->list();
 */
final class LicenceScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $contractorId,
        private readonly int|string $licenceId,
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
        return new LicenceAttachmentFileResource($this->connector, $this->companyId, $this->contractorId, $this->licenceId);
    }
}
