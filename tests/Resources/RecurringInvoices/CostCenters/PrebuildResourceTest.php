<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Prebuilds\PrebuildItem;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Prebuilds\PrebuildListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Prebuilds\CreatePrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Prebuilds\DeletePrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Prebuilds\GetPrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Prebuilds\ListPrebuildsRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Prebuilds\UpdatePrebuildRequest;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters\PrebuildResource;

it('navigates from a cost center scope to its prebuilds', function () {
    expect($this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->prebuilds())
        ->toBeInstanceOf(PrebuildResource::class);
});

it('lists and gets prebuilds through the scope', function () {
    MockClient::global([
        ListPrebuildsRequest::class => MockResponse::fixture('list_recurring_invoice_prebuilds_request'),
        GetPrebuildRequest::class => MockResponse::fixture('get_recurring_invoice_prebuild_request'),
    ]);

    $items = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->prebuilds();

    expect($items->list()->all()[0])->toBeInstanceOf(PrebuildListItem::class)
        ->and($items->get(8501))->toBeInstanceOf(PrebuildItem::class);
});

it('creates, updates and deletes prebuilds at the nested endpoint', function () {
    $mockClient = MockClient::global([
        CreatePrebuildRequest::class => MockResponse::make(['ID' => 8502], 201),
        UpdatePrebuildRequest::class => MockResponse::make([], 204),
        DeletePrebuildRequest::class => MockResponse::make([], 204),
    ]);

    $items = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->prebuilds();

    expect($items->create(['Prebuild' => 310, 'Total' => ['Qty' => 1]]))->toBe(8502);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'POST'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/prebuilds/'
        && $request->body()->all() === ['Prebuild' => 310, 'Total' => ['Qty' => 1]]);

    expect($items->update(8502, ['Discount' => 10])->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'PATCH'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/prebuilds/8502'
        && $request->body()->all() === ['Discount' => 10]);

    expect($items->delete(8502)->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'DELETE'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/prebuilds/8502');
});
