<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ServiceFees\ServiceFeeItem;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ServiceFees\ServiceFeeListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ServiceFees\CreateServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ServiceFees\DeleteServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ServiceFees\GetServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ServiceFees\ListServiceFeesRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ServiceFees\UpdateServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters\ServiceFeeResource;

it('navigates from a cost center scope to its serviceFees', function () {
    expect($this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->serviceFees())
        ->toBeInstanceOf(ServiceFeeResource::class);
});

it('lists and gets serviceFees through the scope', function () {
    MockClient::global([
        ListServiceFeesRequest::class => MockResponse::fixture('list_recurring_invoice_service_fees_request'),
        GetServiceFeeRequest::class => MockResponse::fixture('get_recurring_invoice_service_fee_request'),
    ]);

    $items = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->serviceFees();

    expect($items->list()->all()[0])->toBeInstanceOf(ServiceFeeListItem::class)
        ->and($items->get(8201))->toBeInstanceOf(ServiceFeeItem::class);
});

it('creates, updates and deletes serviceFees at the nested endpoint', function () {
    $mockClient = MockClient::global([
        CreateServiceFeeRequest::class => MockResponse::make(['ID' => 8202], 201),
        UpdateServiceFeeRequest::class => MockResponse::make([], 204),
        DeleteServiceFeeRequest::class => MockResponse::make([], 204),
    ]);

    $items = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->serviceFees();

    expect($items->create(['ServiceFee' => 33, 'Total' => ['Qty' => 1]]))->toBe(8202);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'POST'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/serviceFees/'
        && $request->body()->all() === ['ServiceFee' => 33, 'Total' => ['Qty' => 1]]);

    expect($items->update(8202, ['Discount' => 10])->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'PATCH'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/serviceFees/8202'
        && $request->body()->all() === ['Discount' => 10]);

    expect($items->delete(8202)->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'DELETE'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/serviceFees/8202');
});
