<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Labor\LaborItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Labor\GetLaborRequest;

it('sends get recurring invoice labor item request to correct endpoint', function () {
    MockClient::global([
        GetLaborRequest::class => MockResponse::fixture('get_recurring_invoice_labor_request'),
    ]);

    $request = new GetLaborRequest(0, 501, 61, 7001, 8401);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/labor/8401');
});

it('parses get recurring invoice labor item response correctly', function () {
    MockClient::global([
        GetLaborRequest::class => MockResponse::fixture('get_recurring_invoice_labor_request'),
    ]);

    $dto = $this->sdk->send(new GetLaborRequest(0, 501, 61, 7001, 8401))->dto();

    expect($dto)->toBeInstanceOf(LaborItem::class)
        ->and($dto->id)->toBe(8401)
        ->and($dto->laborType->name)->toBe('Standard Labour')
        ->and($dto->laborRate)->toBe(45.0)
        ->and($dto->projectedTime)->toBe(1.0)
        ->and($dto->ticked)->toBeFalse()
        ->and($dto->salesTaxCode->id)->toBe(6)
        ->and($dto->total->amount->incTax)->toBe(22.5)
        ->and($dto->incomeAccount->number)->toBe('4010');
});
