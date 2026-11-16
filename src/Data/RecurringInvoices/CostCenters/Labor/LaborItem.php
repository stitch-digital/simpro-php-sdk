<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Labor;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\IncomeAccount;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\TaxCode;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ItemSellPrice;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ItemTotal;

/**
 * A labor line on a recurring invoice cost center.
 */
final readonly class LaborItem
{
    public function __construct(
        public int $id,
        public ?Reference $laborType,
        public ?float $laborRate,
        public ?float $laborMarkup,
        public ?TaxCode $salesTaxCode,
        public ?float $projectedTime,
        public ?bool $ticked,
        public ?DateTimeImmutable $dateModified,
        public ?float $discount,
        public ?ItemSellPrice $sellPrice,
        public ?ItemTotal $total,
        public ?IncomeAccount $incomeAccount,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            laborType: ! empty($data['LaborType']) && is_array($data['LaborType']) ? Reference::fromArray($data['LaborType']) : null,
            laborRate: isset($data['LaborRate']) ? (float) $data['LaborRate'] : null,
            laborMarkup: isset($data['LaborMarkup']) ? (float) $data['LaborMarkup'] : null,
            salesTaxCode: ! empty($data['SalesTaxCode']) && is_array($data['SalesTaxCode']) ? TaxCode::fromArray($data['SalesTaxCode']) : null,
            projectedTime: isset($data['ProjectedTime']) ? (float) $data['ProjectedTime'] : null,
            ticked: isset($data['Ticked']) ? (bool) $data['Ticked'] : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            discount: isset($data['Discount']) ? (float) $data['Discount'] : null,
            sellPrice: ! empty($data['SellPrice']) && is_array($data['SellPrice']) ? ItemSellPrice::fromArray($data['SellPrice']) : null,
            total: ! empty($data['Total']) && is_array($data['Total']) ? ItemTotal::fromArray($data['Total']) : null,
            incomeAccount: ! empty($data['IncomeAccount']) && is_array($data['IncomeAccount']) ? IncomeAccount::fromArray($data['IncomeAccount']) : null,
        );
    }
}
