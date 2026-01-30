<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Setup;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomerInvoiceStatusCodeResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\ProjectStatusCodeResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\VendorOrderStatusCodeResource;

/**
 * Scope for navigating status code resources.
 */
final class StatusCodeScope
{
    public function __construct(
        private readonly AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {}

    /**
     * Access customer invoice status code endpoints.
     */
    public function customerInvoices(): CustomerInvoiceStatusCodeResource
    {
        return new CustomerInvoiceStatusCodeResource($this->connector, $this->companyId);
    }

    /**
     * Access project status code endpoints.
     */
    public function projects(): ProjectStatusCodeResource
    {
        return new ProjectStatusCodeResource($this->connector, $this->companyId);
    }

    /**
     * Access vendor order status code endpoints.
     */
    public function vendorOrders(): VendorOrderStatusCodeResource
    {
        return new VendorOrderStatusCodeResource($this->connector, $this->companyId);
    }
}
