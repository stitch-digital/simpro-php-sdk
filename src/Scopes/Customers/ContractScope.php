<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Customers;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Customers\ContractCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Customers\ContractInflationResource;
use Simpro\PhpSdk\Simpro\Resources\Customers\ContractLaborRateResource;
use Simpro\PhpSdk\Simpro\Resources\Customers\ContractServiceLevelResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific contract, providing access to nested resources.
 *
 * @example
 * // Access contract custom fields
 * $connector->customers(companyId: 0)->customer(customerId: 123)->contract(contractId: 100)->customFields()->list();
 *
 * // Access contract inflation
 * $connector->customers(companyId: 0)->customer(customerId: 123)->contract(contractId: 100)->inflation()->list();
 *
 * // Access contract labor rates
 * $connector->customers(companyId: 0)->customer(customerId: 123)->contract(contractId: 100)->laborRates()->list();
 *
 * // Access contract service levels
 * $connector->customers(companyId: 0)->customer(customerId: 123)->contract(contractId: 100)->serviceLevels()->list();
 */
final class ContractScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int|string $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contractId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access custom fields for this contract.
     */
    public function customFields(): ContractCustomFieldResource
    {
        return new ContractCustomFieldResource($this->connector, $this->companyId, $this->customerId, $this->contractId);
    }

    /**
     * Access inflation records for this contract.
     */
    public function inflation(): ContractInflationResource
    {
        return new ContractInflationResource($this->connector, $this->companyId, $this->customerId, $this->contractId);
    }

    /**
     * Access labor rates for this contract.
     */
    public function laborRates(): ContractLaborRateResource
    {
        return new ContractLaborRateResource($this->connector, $this->companyId, $this->customerId, $this->contractId);
    }

    /**
     * Access service levels for this contract.
     */
    public function serviceLevels(): ContractServiceLevelResource
    {
        return new ContractServiceLevelResource($this->connector, $this->companyId, $this->customerId, $this->contractId);
    }

    /**
     * Navigate to a specific service level scope.
     */
    public function serviceLevel(int|string $serviceLevelId): ServiceLevelScope
    {
        return new ServiceLevelScope($this->connector, $this->companyId, $this->customerId, $this->contractId, $serviceLevelId);
    }
}
