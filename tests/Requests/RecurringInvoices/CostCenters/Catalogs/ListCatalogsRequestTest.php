<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Catalogs\CatalogListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Catalogs\ListCatalogsRequest;

it('sends list recurring invoice catalogs request to correct endpoint', function () {
    MockClient::global([
        ListCatalogsRequest::class => MockResponse::fixture('list_recurring_invoice_catalogs_request'),
    ]);

    $request = new ListCatalogsRequest(0, 501, 61, 7001);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/catalogs/');
});

it('parses list recurring invoice catalogs response correctly', function () {
    MockClient::global([
        ListCatalogsRequest::class => MockResponse::fixture('list_recurring_invoice_catalogs_request'),
    ]);

    $dto = $this->sdk->send(new ListCatalogsRequest(0, 501, 61, 7001))->dto();

    expect($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(CatalogListItem::class)
        ->and($dto[0]->id)->toBe(8301)
        ->and($dto[0]->catalog->partNo)->toBe('QS-IQ4')
        ->and($dto[0]->basePrice)->toBe(12.0)
        ->and($dto[0]->total->qty)->toBe(1.0);
});
