<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\RecurringInvoice;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\RecurringInvoiceListItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CreateRecurringInvoiceRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\DeleteRecurringInvoiceRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\GetRecurringInvoiceRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\ListRecurringInvoicesDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\ListRecurringInvoicesRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\UpdateRecurringInvoiceRequest;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoiceResource;
use Simpro\PhpSdk\Simpro\Scopes\RecurringInvoices\RecurringInvoiceScope;

it('exposes recurringInvoices() on the connector returning the resource', function () {
    expect($this->sdk->recurringInvoices(companyId: 0))->toBeInstanceOf(RecurringInvoiceResource::class);
});

it('list() returns a QueryBuilder that resolves to list items', function () {
    MockClient::global([
        ListRecurringInvoicesRequest::class => MockResponse::fixture('list_recurring_invoices_request'),
    ]);

    $query = $this->sdk->recurringInvoices(companyId: 0)->list(['Archived' => 'false']);

    expect($query)->toBeInstanceOf(QueryBuilder::class);

    $items = $query->all();

    expect($items)->toHaveCount(2)
        ->and($items[0])->toBeInstanceOf(RecurringInvoiceListItem::class)
        ->and($items[0]->id)->toBe(501);
});

it('listDetailed() returns a QueryBuilder that resolves to full DTOs', function () {
    MockClient::global([
        ListRecurringInvoicesDetailedRequest::class => MockResponse::make([
            json_decode(file_get_contents(__DIR__.'/../Fixtures/Saloon/get_recurring_invoice_request.json'), true)['data'],
        ]),
    ]);

    $items = $this->sdk->recurringInvoices(companyId: 0)->listDetailed()->all();

    expect($items)->toHaveCount(1)
        ->and($items[0])->toBeInstanceOf(RecurringInvoice::class)
        ->and($items[0]->id)->toBe(501);
});

it('get() returns the DTO and forwards requested columns', function () {
    $mockClient = MockClient::global([
        GetRecurringInvoiceRequest::class => MockResponse::fixture('get_recurring_invoice_request'),
    ]);

    $dto = $this->sdk->recurringInvoices(companyId: 0)->get(501, ['ID', 'Name']);

    expect($dto)->toBeInstanceOf(RecurringInvoice::class)
        ->and($dto->id)->toBe(501);

    $mockClient->assertSent(fn (Request $request, Response $response) => (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501?columns=ID%2CName');
});

it('create() posts the body and returns the new ID', function () {
    $mockClient = MockClient::global([
        CreateRecurringInvoiceRequest::class => MockResponse::make(['ID' => 777], 201),
    ]);

    $id = $this->sdk->recurringInvoices(companyId: 0)->create([
        'Type' => 'Service',
        'Site' => 9001,
        'Customer' => 2048,
        'RecurringFrequency' => 'month',
    ]);

    expect($id)->toBe(777);

    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'POST'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/'
        && $request->body()->all() === ['Type' => 'Service', 'Site' => 9001, 'Customer' => 2048, 'RecurringFrequency' => 'month']);
});

it('update() patches the recurring invoice', function () {
    $mockClient = MockClient::global([
        UpdateRecurringInvoiceRequest::class => MockResponse::make([], 204),
    ]);

    $response = $this->sdk->recurringInvoices(companyId: 0)->update(501, ['InflationRate' => 4]);

    expect($response->status())->toBe(204);

    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'PATCH'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501'
        && $request->body()->all() === ['InflationRate' => 4]);
});

it('delete() removes the recurring invoice', function () {
    $mockClient = MockClient::global([
        DeleteRecurringInvoiceRequest::class => MockResponse::make([], 204),
    ]);

    $response = $this->sdk->recurringInvoices(companyId: 0)->delete(501);

    expect($response->status())->toBe(204);

    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'DELETE'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501');
});

it('bulkCreate() posts to the collection with the bulk header', function () {
    $mockClient = MockClient::global([
        BulkCreateRequest::class => MockResponse::fixture('bulk_create_request'),
    ]);

    $result = $this->sdk->recurringInvoices(companyId: 0)->bulkCreate([
        ['Type' => 'Service', 'Site' => 1],
    ]);

    expect($result)->toBeInstanceOf(BulkResponse::class);

    $mockClient->assertSent(fn (Request $request, Response $response) => (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/multiple/');
});

it('recurringInvoice() opens a scope for nested resources', function () {
    expect($this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501))->toBeInstanceOf(RecurringInvoiceScope::class);
});
