<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Info\Info;
use Simpro\PhpSdk\Simpro\Requests\Info\InfoRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class InfoResource extends BaseResource
{
    /**
     * Get complete information about the Simpro instance.
     */
    public function get(): Info
    {
        $request = new InfoRequest;

        return $this->connector->send($request)->dto();
    }

    /**
     * Get the Simpro version.
     */
    public function version(): string
    {
        return $this->get()->version;
    }

    /**
     * Get the Simpro country.
     */
    public function country(): string
    {
        return $this->get()->country;
    }

    /**
     * Check if maintenance planner is enabled.
     */
    public function maintenancePlanner(): bool
    {
        return $this->get()->maintenancePlanner;
    }

    /**
     * Check if multi-company is enabled.
     */
    public function multiCompany(): bool
    {
        return $this->get()->multiCompany;
    }

    /**
     * Check if shared catalog is enabled.
     */
    public function sharedCatalog(): bool
    {
        return $this->get()->sharedCatalog;
    }

    /**
     * Check if shared stock is enabled.
     */
    public function sharedStock(): bool
    {
        return $this->get()->sharedStock;
    }

    /**
     * Check if shared clients is enabled.
     */
    public function sharedClients(): bool
    {
        return $this->get()->sharedClients;
    }

    /**
     * Check if shared setup is enabled.
     */
    public function sharedSetup(): bool
    {
        return $this->get()->sharedSetup;
    }

    /**
     * Check if shared defaults is enabled.
     */
    public function sharedDefaults(): bool
    {
        return $this->get()->sharedDefaults;
    }

    /**
     * Check if shared accounts integration is enabled.
     */
    public function sharedAccountsIntegration(): bool
    {
        return $this->get()->sharedAccountsIntegration;
    }

    /**
     * Check if shared VOIP is enabled.
     */
    public function sharedVoip(): bool
    {
        return $this->get()->sharedVoip;
    }
}
