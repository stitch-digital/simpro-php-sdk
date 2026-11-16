<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Catalogs\CatalogItem;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Catalogs\CatalogListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Catalogs\CreateCatalogRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Catalogs\DeleteCatalogRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Catalogs\GetCatalogRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Catalogs\ListCatalogsRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Catalogs\UpdateCatalogRequest;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters\CatalogResource;

it('navigates from a cost center scope to its catalogs', function () {
    expect($this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->catalogs())
        ->toBeInstanceOf(CatalogResource::class);
});

it('lists and gets catalogs through the scope', function () {
    MockClient::global([
        ListCatalogsRequest::class => MockResponse::fixture('list_recurring_invoice_catalogs_request'),
        GetCatalogRequest::class => MockResponse::fixture('get_recurring_invoice_catalog_request'),
    ]);

    $items = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->catalogs();

    expect($items->list()->all()[0])->toBeInstanceOf(CatalogListItem::class)
        ->and($items->get(8301))->toBeInstanceOf(CatalogItem::class);
});

it('creates, updates and deletes catalogs at the nested endpoint', function () {
    $mockClient = MockClient::global([
        CreateCatalogRequest::class => MockResponse::make(['ID' => 8302], 201),
        UpdateCatalogRequest::class => MockResponse::make([], 204),
        DeleteCatalogRequest::class => MockResponse::make([], 204),
    ]);

    $items = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->catalogs();

    expect($items->create(['Catalog' => 1200, 'Total' => ['Qty' => 1]]))->toBe(8302);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'POST'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/catalogs/'
        && $request->body()->all() === ['Catalog' => 1200, 'Total' => ['Qty' => 1]]);

    expect($items->update(8302, ['Discount' => 10])->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'PATCH'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/catalogs/8302'
        && $request->body()->all() === ['Discount' => 10]);

    expect($items->delete(8302)->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'DELETE'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/catalogs/8302');
});
