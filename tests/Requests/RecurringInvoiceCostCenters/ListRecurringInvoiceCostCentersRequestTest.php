<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoiceCostCenters\RecurringInvoiceCostCenterListItem;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\RecurringInvoiceType;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoiceCostCenters\ListRecurringInvoiceCostCentersRequest;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoiceCostCenterResource;

it('sends list recurring invoice cost centers request to correct endpoint', function () {
    MockClient::global([
        ListRecurringInvoiceCostCentersRequest::class => MockResponse::fixture('list_recurring_invoice_cost_centers_request'),
    ]);

    $request = new ListRecurringInvoiceCostCentersRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoiceCostCenters/');
});

it('parses list recurring invoice cost centers response correctly', function () {
    MockClient::global([
        ListRecurringInvoiceCostCentersRequest::class => MockResponse::fixture('list_recurring_invoice_cost_centers_request'),
    ]);

    $dto = $this->sdk->send(new ListRecurringInvoiceCostCentersRequest(0))->dto();

    expect($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(RecurringInvoiceCostCenterListItem::class)
        ->and($dto[0]->id)->toBe(7001)
        ->and($dto[0]->costCenter->id)->toBe(458)
        ->and($dto[0]->name)->toBe('Monitoring - Intruder Alarms')
        ->and($dto[0]->recurringInvoice->id)->toBe(501)
        ->and($dto[0]->recurringInvoice->type)->toBe(RecurringInvoiceType::Service)
        ->and($dto[0]->recurringInvoice->name)->toBe('Qolsys subscription')
        ->and($dto[0]->section->id)->toBe(61)
        ->and($dto[0]->dateModified->format('Y-m-d'))->toBe('2026-11-16')
        ->and($dto[0]->href)->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001')
        ->and($dto[1]->costCenter->id)->toBe(485);
});

it('exposes recurringInvoiceCostCenters() on the connector with a list() QueryBuilder', function () {
    MockClient::global([
        ListRecurringInvoiceCostCentersRequest::class => MockResponse::fixture('list_recurring_invoice_cost_centers_request'),
    ]);

    $resource = $this->sdk->recurringInvoiceCostCenters(companyId: 0);

    expect($resource)->toBeInstanceOf(RecurringInvoiceCostCenterResource::class)
        ->and($resource->list())->toBeInstanceOf(QueryBuilder::class)
        ->and($resource->list(['RecurringInvoice.ID' => 501])->all())->toHaveCount(2);
});
