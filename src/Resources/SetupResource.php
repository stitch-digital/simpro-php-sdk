<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Setup\AccountingCategoryResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\ActivityResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\AssetServiceLevelResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes\AssetTypeResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\BusinessGroupResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\ChartOfAccountResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CostCenterResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CurrencyResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomerGroupResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\CustomerProfileResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\DefaultsResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\PaymentMethodResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\PaymentTermResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\QuoteArchiveReasonResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\ResponseTimeResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\SecurityGroupResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\TaskCategoryResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\TaxCodeResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\TeamResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\WebhookResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\ZoneResource;
use Simpro\PhpSdk\Simpro\Scopes\Setup\AssetTypes\AssetTypeScope;
use Simpro\PhpSdk\Simpro\Scopes\Setup\CommissionScope;
use Simpro\PhpSdk\Simpro\Scopes\Setup\CustomFieldScope;
use Simpro\PhpSdk\Simpro\Scopes\Setup\LaborScope;
use Simpro\PhpSdk\Simpro\Scopes\Setup\MaterialsScope;
use Simpro\PhpSdk\Simpro\Scopes\Setup\StatusCodeScope;
use Simpro\PhpSdk\Simpro\Scopes\Setup\TagScope;

/**
 * Entry point for all Setup resources.
 *
 * @property AbstractSimproConnector $connector
 */
final class SetupResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * Access webhook subscription endpoints.
     */
    public function webhooks(): WebhookResource
    {
        return new WebhookResource($this->connector, $this->companyId);
    }

    /**
     * Access tax code endpoints.
     */
    public function taxCodes(): TaxCodeResource
    {
        return new TaxCodeResource($this->connector, $this->companyId);
    }

    /**
     * Access payment method endpoints.
     */
    public function paymentMethods(): PaymentMethodResource
    {
        return new PaymentMethodResource($this->connector, $this->companyId);
    }

    /**
     * Access payment term endpoints.
     */
    public function paymentTerms(): PaymentTermResource
    {
        return new PaymentTermResource($this->connector, $this->companyId);
    }

    /**
     * Access customer group endpoints.
     */
    public function customerGroups(): CustomerGroupResource
    {
        return new CustomerGroupResource($this->connector, $this->companyId);
    }

    /**
     * Access zone endpoints.
     */
    public function zones(): ZoneResource
    {
        return new ZoneResource($this->connector, $this->companyId);
    }

    /**
     * Access accounting category endpoints.
     */
    public function accountingCategories(): AccountingCategoryResource
    {
        return new AccountingCategoryResource($this->connector, $this->companyId);
    }

    /**
     * Access business group endpoints.
     */
    public function businessGroups(): BusinessGroupResource
    {
        return new BusinessGroupResource($this->connector, $this->companyId);
    }

    /**
     * Access chart of accounts endpoints.
     */
    public function chartOfAccounts(): ChartOfAccountResource
    {
        return new ChartOfAccountResource($this->connector, $this->companyId);
    }

    /**
     * Access cost center endpoints.
     */
    public function costCenters(): CostCenterResource
    {
        return new CostCenterResource($this->connector, $this->companyId);
    }

    /**
     * Access custom field endpoints.
     */
    public function customFields(): CustomFieldScope
    {
        return new CustomFieldScope($this->connector, $this->companyId);
    }

    /**
     * Access asset type endpoints.
     */
    public function assetTypes(): AssetTypeResource
    {
        return new AssetTypeResource($this->connector, $this->companyId);
    }

    /**
     * Navigate to a specific asset type's nested resources.
     */
    public function assetType(int|string $assetTypeId): AssetTypeScope
    {
        return new AssetTypeScope($this->connector, $this->companyId, $assetTypeId);
    }

    /**
     * Access labor-related setup resources.
     */
    public function labor(): LaborScope
    {
        return new LaborScope($this->connector, $this->companyId);
    }

    /**
     * Access materials-related setup resources.
     */
    public function materials(): MaterialsScope
    {
        return new MaterialsScope($this->connector, $this->companyId);
    }

    /**
     * Access activity endpoints.
     */
    public function activities(): ActivityResource
    {
        return new ActivityResource($this->connector, $this->companyId);
    }

    /**
     * Access quote archive reason endpoints.
     */
    public function quoteArchiveReasons(): QuoteArchiveReasonResource
    {
        return new QuoteArchiveReasonResource($this->connector, $this->companyId);
    }

    /**
     * Access asset service level endpoints.
     */
    public function assetServiceLevels(): AssetServiceLevelResource
    {
        return new AssetServiceLevelResource($this->connector, $this->companyId);
    }

    /**
     * Access commission endpoints.
     */
    public function commissions(): CommissionScope
    {
        return new CommissionScope($this->connector, $this->companyId);
    }

    /**
     * Access currency endpoints.
     */
    public function currencies(): CurrencyResource
    {
        return new CurrencyResource($this->connector, $this->companyId);
    }

    /**
     * Access customer profile endpoints.
     */
    public function customerProfiles(): CustomerProfileResource
    {
        return new CustomerProfileResource($this->connector, $this->companyId);
    }

    /**
     * Access company defaults.
     */
    public function defaults(): DefaultsResource
    {
        return new DefaultsResource($this->connector, $this->companyId);
    }

    /**
     * Access response time endpoints.
     */
    public function responseTimes(): ResponseTimeResource
    {
        return new ResponseTimeResource($this->connector, $this->companyId);
    }

    /**
     * Access security group endpoints.
     */
    public function securityGroups(): SecurityGroupResource
    {
        return new SecurityGroupResource($this->connector, $this->companyId);
    }

    /**
     * Access status code endpoints.
     */
    public function statusCodes(): StatusCodeScope
    {
        return new StatusCodeScope($this->connector, $this->companyId);
    }

    /**
     * Access tag endpoints.
     */
    public function tags(): TagScope
    {
        return new TagScope($this->connector, $this->companyId);
    }

    /**
     * Access task category endpoints.
     */
    public function taskCategories(): TaskCategoryResource
    {
        return new TaskCategoryResource($this->connector, $this->companyId);
    }

    /**
     * Access team endpoints.
     */
    public function teams(): TeamResource
    {
        return new TeamResource($this->connector, $this->companyId);
    }
}
