<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Setup;

use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\CatalogCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\ContactCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\ContractorCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\ContractorInvoiceCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\ContractorJobCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\CustomerContactCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\CustomerContractCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\CustomerCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\EmployeeCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\InvoiceCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\PrebuildCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\ProjectCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\SiteContactCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\SiteCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\TaskCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\VendorContactCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\VendorCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\VendorOrderCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomFields\WorkOrderCustomFieldResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for accessing custom field resources.
 */
final class CustomFieldScope extends AbstractScope
{
    public function catalogs(): CatalogCustomFieldResource
    {
        return new CatalogCustomFieldResource($this->connector, $this->companyId);
    }

    public function contacts(): ContactCustomFieldResource
    {
        return new ContactCustomFieldResource($this->connector, $this->companyId);
    }

    public function contractorInvoices(): ContractorInvoiceCustomFieldResource
    {
        return new ContractorInvoiceCustomFieldResource($this->connector, $this->companyId);
    }

    public function contractorJobs(): ContractorJobCustomFieldResource
    {
        return new ContractorJobCustomFieldResource($this->connector, $this->companyId);
    }

    public function contractors(): ContractorCustomFieldResource
    {
        return new ContractorCustomFieldResource($this->connector, $this->companyId);
    }

    public function customerContacts(): CustomerContactCustomFieldResource
    {
        return new CustomerContactCustomFieldResource($this->connector, $this->companyId);
    }

    public function customerContracts(): CustomerContractCustomFieldResource
    {
        return new CustomerContractCustomFieldResource($this->connector, $this->companyId);
    }

    public function customers(): CustomerCustomFieldResource
    {
        return new CustomerCustomFieldResource($this->connector, $this->companyId);
    }

    public function employees(): EmployeeCustomFieldResource
    {
        return new EmployeeCustomFieldResource($this->connector, $this->companyId);
    }

    public function invoices(): InvoiceCustomFieldResource
    {
        return new InvoiceCustomFieldResource($this->connector, $this->companyId);
    }

    public function prebuilds(): PrebuildCustomFieldResource
    {
        return new PrebuildCustomFieldResource($this->connector, $this->companyId);
    }

    public function projects(): ProjectCustomFieldResource
    {
        return new ProjectCustomFieldResource($this->connector, $this->companyId);
    }

    public function siteContacts(): SiteContactCustomFieldResource
    {
        return new SiteContactCustomFieldResource($this->connector, $this->companyId);
    }

    public function sites(): SiteCustomFieldResource
    {
        return new SiteCustomFieldResource($this->connector, $this->companyId);
    }

    public function tasks(): TaskCustomFieldResource
    {
        return new TaskCustomFieldResource($this->connector, $this->companyId);
    }

    public function vendorContacts(): VendorContactCustomFieldResource
    {
        return new VendorContactCustomFieldResource($this->connector, $this->companyId);
    }

    public function vendorOrders(): VendorOrderCustomFieldResource
    {
        return new VendorOrderCustomFieldResource($this->connector, $this->companyId);
    }

    public function vendors(): VendorCustomFieldResource
    {
        return new VendorCustomFieldResource($this->connector, $this->companyId);
    }

    public function workOrders(): WorkOrderCustomFieldResource
    {
        return new WorkOrderCustomFieldResource($this->connector, $this->companyId);
    }
}
