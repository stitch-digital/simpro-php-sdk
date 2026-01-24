<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Data\Clients\Sector;
use Simpro\PhpSdk\Simpro\Paginators\SimproPaginator;
use Simpro\PhpSdk\Simpro\Requests\Clients\ListClientsRequest;
use Simpro\PhpSdk\Simpro\Simpro;

/**
 * @property Simpro $connector
 */
final class ClientResource extends BaseResource
{
    /**
     * List clients with any supported filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): SimproPaginator
    {
        $request = new ListClientsRequest;

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return $this->connector->paginate($request);
    }

    public function listActive(): SimproPaginator
    {
        return $this->list(['is_active' => true]);
    }

    public function listInactive(): SimproPaginator
    {
        return $this->list(['is_active' => false]);
    }

    public function findById(int|string $clientId): SimproPaginator
    {
        return $this->list(['id' => $clientId]);
    }

    public function findByName(string $clientName, bool $strict = false): SimproPaginator
    {
        return $this->list(
            $strict ? ['name' => $clientName] : ['name_contains' => $clientName]
        );
    }

    public function search(string $query): SimproPaginator
    {
        return $this->list(['text_contains' => $query]);
    }

    public function whereAccountManager(int|array $ids): SimproPaginator
    {
        return $this->list(['account_manager' => $ids]);
    }

    public function whereNotAccountManager(int|array $ids): SimproPaginator
    {
        return $this->list(['not_account_manager' => $ids]);
    }

    public function whereBillingCard(int|array $ids): SimproPaginator
    {
        return $this->list(['billingcard' => $ids]);
    }

    public function whereNotBillingCard(int|array $ids): SimproPaginator
    {
        return $this->list(['not_billingcard' => $ids]);
    }

    public function createdBefore(string $isoDateTime): SimproPaginator
    {
        return $this->list(['created_before' => $isoDateTime]);
    }

    public function createdAfter(string $isoDateTime): SimproPaginator
    {
        return $this->list(['created_after' => $isoDateTime]);
    }

    public function updatedBefore(string $isoDateTime): SimproPaginator
    {
        return $this->list(['updated_before' => $isoDateTime]);
    }

    public function updatedAfter(string $isoDateTime): SimproPaginator
    {
        return $this->list(['updated_after' => $isoDateTime]);
    }

    public function wherePriceTier(int|array $ids): SimproPaginator
    {
        return $this->list(['pricetier' => $ids]);
    }

    public function whereNotPriceTier(int|array $ids): SimproPaginator
    {
        return $this->list(['not_pricetier' => $ids]);
    }

    public function whereIsActive(bool $isActive = true): SimproPaginator
    {
        return $this->list(['is_active' => $isActive]);
    }

    public function whereReportWhitelabel(bool $value): SimproPaginator
    {
        return $this->list(['report_whitelabel' => $value]);
    }

    public function whereReportManual(bool $value): SimproPaginator
    {
        return $this->list(['report_manual' => $value]);
    }

    public function whereBillingManual(bool $value): SimproPaginator
    {
        return $this->list(['billing_manual' => $value]);
    }

    public function whereBillingFixedPrice(bool $value): SimproPaginator
    {
        return $this->list(['billing_fixedprice' => $value]);
    }

    public function whereQuotingAutoRemindersEnabled(bool $value): SimproPaginator
    {
        return $this->list(['quoting_autoreminders_enabled' => $value]);
    }

    /**
     * Filter clients by sector.
     *
     * @param  Sector|array<Sector>|string|array<string>  $sectors
     */
    public function whereSector(Sector|array|string $sectors): SimproPaginator
    {
        $sectorValues = $this->normalizeSectorValues($sectors);

        return $this->list(['sector' => $sectorValues]);
    }

    /**
     * Filter clients by excluding sectors.
     *
     * @param  Sector|array<Sector>|string|array<string>  $sectors
     */
    public function whereNotSector(Sector|array|string $sectors): SimproPaginator
    {
        $sectorValues = $this->normalizeSectorValues($sectors);

        return $this->list(['not_sector' => $sectorValues]);
    }

    /**
     * Normalize sector values to strings for API requests.
     *
     * @param  Sector|array<Sector>|string|array<string>  $sectors
     * @return string|array<string>
     */
    private function normalizeSectorValues(Sector|array|string $sectors): string|array
    {
        if ($sectors instanceof Sector) {
            return $sectors->value;
        }

        if (is_array($sectors)) {
            return array_map(function ($sector) {
                return $sector instanceof Sector ? $sector->value : (string) $sector;
            }, $sectors);
        }

        return (string) $sectors;
    }

    public function whereHasProperties(bool $value = true): SimproPaginator
    {
        return $this->list(['has_properties' => $value]);
    }

    public function whereIsDuplicated(bool $value = true): SimproPaginator
    {
        return $this->list(['is_duplicated' => $value]);
    }

    public function whereParentClientGroup(int|string $clientGroupId): SimproPaginator
    {
        return $this->list(['parent_clientgroup' => $clientGroupId]);
    }

    public function whereClientGroup(int|string $clientGroupId): SimproPaginator
    {
        return $this->list(['clientgroup' => $clientGroupId]);
    }

    public function whereNotClientGroup(int|string $clientGroupId): SimproPaginator
    {
        return $this->list(['not_clientgroup' => $clientGroupId]);
    }

    public function whereBranch(int|array $branchIds): SimproPaginator
    {
        return $this->list(['branch' => $branchIds]);
    }

    public function whereNotBranch(int|array $branchIds): SimproPaginator
    {
        return $this->list(['not_branch' => $branchIds]);
    }

    public function whereHasAccount(bool $value = true): SimproPaginator
    {
        return $this->list(['has_account' => $value]);
    }

    public function whereHasActiveProperty(bool $value = true): SimproPaginator
    {
        return $this->list(['has_active_property' => $value]);
    }

    public function whereTags(int|array $tagIds): SimproPaginator
    {
        return $this->list(['tags' => $tagIds]);
    }

    public function whereNotTags(int|array $tagIds): SimproPaginator
    {
        return $this->list(['not_tags' => $tagIds]);
    }

    public function whereHasBusinessHours(bool $value = true): SimproPaginator
    {
        return $this->list(['has_business_hours' => $value]);
    }

    public function wherePhoneNumberContains(string $needle): SimproPaginator
    {
        return $this->list(['phone_number_contains' => $needle]);
    }

    public function updatedSince(string $isoDateTime): SimproPaginator
    {
        return $this->list(['updatedsince' => $isoDateTime]);
    }

    public function whereExtraFields(array $extraFields): SimproPaginator
    {
        return $this->list(['extra_fields' => $extraFields]);
    }
}
