<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\OneOffs\OneOffItem;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\OneOffs\OneOffListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\OneOffs\CreateOneOffRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\OneOffs\DeleteOneOffRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\OneOffs\GetOneOffRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\OneOffs\ListOneOffsRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\OneOffs\UpdateOneOffRequest;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters\OneOffResource;

it('navigates from a cost center scope to its oneOffs', function () {
    expect($this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->oneOffs())
        ->toBeInstanceOf(OneOffResource::class);
});

it('lists and gets oneOffs through the scope', function () {
    MockClient::global([
        ListOneOffsRequest::class => MockResponse::fixture('list_recurring_invoice_one_offs_request'),
        GetOneOffRequest::class => MockResponse::fixture('get_recurring_invoice_one_off_request'),
    ]);

    $items = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->oneOffs();

    expect($items->list()->all()[0])->toBeInstanceOf(OneOffListItem::class)
        ->and($items->get(8101))->toBeInstanceOf(OneOffItem::class);
});

it('creates, updates and deletes oneOffs at the nested endpoint', function () {
    $mockClient = MockClient::global([
        CreateOneOffRequest::class => MockResponse::make(['ID' => 8102], 201),
        UpdateOneOffRequest::class => MockResponse::make([], 204),
        DeleteOneOffRequest::class => MockResponse::make([], 204),
    ]);

    $items = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenter(7001)->oneOffs();

    expect($items->create(['Type' => 'Material', 'Description' => 'Qolsys subscription', 'Total' => ['Qty' => 1]]))->toBe(8102);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'POST'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/oneOffs/'
        && $request->body()->all() === ['Type' => 'Material', 'Description' => 'Qolsys subscription', 'Total' => ['Qty' => 1]]);

    expect($items->update(8102, ['Discount' => 10])->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'PATCH'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/oneOffs/8102'
        && $request->body()->all() === ['Discount' => 10]);

    expect($items->delete(8102)->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'DELETE'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/oneOffs/8102');
});
