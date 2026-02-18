<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Quotes;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Quotes\QuoteCostCenterResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

final class QuoteSectionScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $quoteId,
        private readonly int|string $sectionId,
    ) {
        parent::__construct($connector, $companyId);
    }

    public function costCenters(): QuoteCostCenterResource
    {
        return new QuoteCostCenterResource($this->connector, $this->companyId, $this->quoteId, $this->sectionId);
    }

    public function costCenter(int|string $costCenterId): QuoteCostCenterScope
    {
        return new QuoteCostCenterScope($this->connector, $this->companyId, $this->quoteId, $this->sectionId, $costCenterId);
    }
}
