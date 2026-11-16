<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\CostCenter;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\GetCostCenterRequest;

it('sends get recurring invoice cost center request to correct endpoint', function () {
    MockClient::global([
        GetCostCenterRequest::class => MockResponse::fixture('get_recurring_invoice_section_cost_center_request'),
    ]);

    $request = new GetCostCenterRequest(0, 501, 61, 7001);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001');
});

it('parses get recurring invoice cost center response correctly', function () {
    MockClient::global([
        GetCostCenterRequest::class => MockResponse::fixture('get_recurring_invoice_section_cost_center_request'),
    ]);

    $dto = $this->sdk->send(new GetCostCenterRequest(0, 501, 61, 7001))->dto();

    expect($dto)->toBeInstanceOf(CostCenter::class)
        ->and($dto->id)->toBe(7001)
        ->and($dto->costCenter->id)->toBe(458)
        ->and($dto->recurringId)->toBe(501)
        ->and($dto->name)->toBe('Monitoring - Intruder Alarms')
        ->and($dto->header)->toBe('Qolsys subscription')
        ->and($dto->site->id)->toBe(9001)
        ->and($dto->description)->toBe('<p>Qolsys monitoring</p>')
        ->and($dto->notes)->toBe('')
        ->and($dto->orderNo)->toBe('PO-1')
        ->and($dto->displayOrder)->toBe(1)
        ->and($dto->itemsLocked)->toBeFalse()
        ->and($dto->revizedLocked)->toBeFalse()
        ->and($dto->total->exTax)->toBe(18.75)
        ->and($dto->total->taxCode->id)->toBe(6)
        ->and($dto->totals->materialsMarkup->estimate)->toBe(6.75)
        ->and($dto->dateModified->format('Y-m-d'))->toBe('2026-11-16');
});
