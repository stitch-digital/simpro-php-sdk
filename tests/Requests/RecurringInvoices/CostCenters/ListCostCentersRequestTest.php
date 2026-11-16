<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\CostCenterListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ListCostCentersRequest;

it('sends list recurring invoice cost centers request to correct endpoint', function () {
    MockClient::global([
        ListCostCentersRequest::class => MockResponse::fixture('list_recurring_invoice_section_cost_centers_request'),
    ]);

    $request = new ListCostCentersRequest(0, 501, 61);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/');
});

it('parses list recurring invoice cost centers response correctly', function () {
    MockClient::global([
        ListCostCentersRequest::class => MockResponse::fixture('list_recurring_invoice_section_cost_centers_request'),
    ]);

    $dto = $this->sdk->send(new ListCostCentersRequest(0, 501, 61))->dto();

    expect($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CostCenterListItem::class)
        ->and($dto[0]->id)->toBe(7001)
        ->and($dto[0]->costCenter->id)->toBe(458)
        ->and($dto[0]->costCenter->name)->toBe('Monitoring - Intruder Alarms')
        ->and($dto[0]->recurringId)->toBe(501)
        ->and($dto[0]->displayOrder)->toBe(1)
        ->and($dto[0]->total->incTax)->toBe(22.5)
        ->and($dto[0]->total->taxCode->code)->toBe('VAT20')
        ->and($dto[1]->costCenter->id)->toBe(485);
});
