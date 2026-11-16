<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\RecurringInvoice;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\RecurringInvoiceListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\ListRecurringInvoicesDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\ListRecurringInvoicesRequest;

it('sends list recurring invoices request to correct endpoint', function () {
    MockClient::global([
        ListRecurringInvoicesRequest::class => MockResponse::fixture('list_recurring_invoices_request'),
    ]);

    $request = new ListRecurringInvoicesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/');
});

it('parses list recurring invoices response correctly', function () {
    MockClient::global([
        ListRecurringInvoicesRequest::class => MockResponse::fixture('list_recurring_invoices_request'),
    ]);

    $dto = $this->sdk->send(new ListRecurringInvoicesRequest(0))->dto();

    expect($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(RecurringInvoiceListItem::class)
        ->and($dto[0]->id)->toBe(501)
        ->and($dto[0]->description)->toBe('<p>Monthly Qolsys monitoring and maintenance</p>')
        ->and($dto[0]->total->incTax)->toBe(35.0)
        ->and($dto[1]->id)->toBe(502)
        ->and($dto[1]->total->exTax)->toBe(18.75);
});

it('requests every column for the detailed list and hydrates full DTOs', function () {
    MockClient::global([
        ListRecurringInvoicesDetailedRequest::class => MockResponse::make([
            json_decode(file_get_contents(__DIR__.'/../../Fixtures/Saloon/get_recurring_invoice_request.json'), true)['data'],
        ]),
    ]);

    $request = new ListRecurringInvoicesDetailedRequest(0);
    $dto = $this->sdk->send($request)->dto();

    $columns = explode(',', $request->query()->get('columns'));

    expect($request->query()->get('display'))->toBe('all')
        ->and($columns)->toContain('InflationRate', 'InflationAnniversaryDate', 'NextRecurringDate', 'CustomerContract', 'Technician', 'Archived')
        ->and($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(RecurringInvoice::class)
        ->and($dto[0]->name)->toBe('Qolsys subscription');
});
