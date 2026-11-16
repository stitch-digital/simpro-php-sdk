<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\BillableStatus;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Catalogs\CatalogItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Catalogs\GetCatalogRequest;

it('sends get recurring invoice catalogs item request to correct endpoint', function () {
    MockClient::global([
        GetCatalogRequest::class => MockResponse::fixture('get_recurring_invoice_catalog_request'),
    ]);

    $request = new GetCatalogRequest(0, 501, 61, 7001, 8301);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/catalogs/8301');
});

it('parses get recurring invoice catalogs item response correctly', function () {
    MockClient::global([
        GetCatalogRequest::class => MockResponse::fixture('get_recurring_invoice_catalog_request'),
    ]);

    $dto = $this->sdk->send(new GetCatalogRequest(0, 501, 61, 7001, 8301))->dto();

    expect($dto)->toBeInstanceOf(CatalogItem::class)
        ->and($dto->id)->toBe(8301)
        ->and($dto->catalog->id)->toBe(1200)
        ->and($dto->catalog->partNo)->toBe('QS-IQ4')
        ->and($dto->billableStatus)->toBe(BillableStatus::Billable)
        ->and($dto->basePrice)->toBe(12.0)
        ->and($dto->markup)->toBe(56.25)
        ->and($dto->fitTime->name)->toBe('Normal')
        ->and($dto->totals->priceEx)->toBe(18.75)
        ->and($dto->sellPrice->incTax)->toBe(22.5)
        ->and($dto->incomeAccount->name)->toBe('Monitoring income');
});
