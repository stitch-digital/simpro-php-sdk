<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Labor\LaborItem;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Labor\LaborListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Labor\CreateLaborRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Labor\DeleteLaborRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Labor\GetLaborRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Labor\ListLaborRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Labor\UpdateLaborRequest;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters\LaborResource;

it('navigates from a cost center scope to its labor', function () {
    expect($this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->labor())
        ->toBeInstanceOf(LaborResource::class);
});

it('lists and gets labor through the scope', function () {
    MockClient::global([
        ListLaborRequest::class => MockResponse::fixture('list_recurring_invoice_labors_request'),
        GetLaborRequest::class => MockResponse::fixture('get_recurring_invoice_labor_request'),
    ]);

    $items = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->labor();

    expect($items->list()->all()[0])->toBeInstanceOf(LaborListItem::class)
        ->and($items->get(8401))->toBeInstanceOf(LaborItem::class);
});

it('creates, updates and deletes labor at the nested endpoint', function () {
    $mockClient = MockClient::global([
        CreateLaborRequest::class => MockResponse::make(['ID' => 8402], 201),
        UpdateLaborRequest::class => MockResponse::make([], 204),
        DeleteLaborRequest::class => MockResponse::make([], 204),
    ]);

    $items = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->labor();

    expect($items->create(['LaborType' => 4, 'Total' => ['Qty' => 1]]))->toBe(8402);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'POST'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/labor/'
        && $request->body()->all() === ['LaborType' => 4, 'Total' => ['Qty' => 1]]);

    expect($items->update(8402, ['Discount' => 10])->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'PATCH'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/labor/8402'
        && $request->body()->all() === ['Discount' => 10]);

    expect($items->delete(8402)->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'DELETE'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/labor/8402');
});
