<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\BillableStatus;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ServiceFees\ServiceFeeItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ServiceFees\GetServiceFeeRequest;

it('sends get recurring invoice serviceFees item request to correct endpoint', function () {
    MockClient::global([
        GetServiceFeeRequest::class => MockResponse::fixture('get_recurring_invoice_service_fee_request'),
    ]);

    $request = new GetServiceFeeRequest(0, 501, 61, 7001, 8201);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/serviceFees/8201');
});

it('parses get recurring invoice serviceFees item response correctly', function () {
    MockClient::global([
        GetServiceFeeRequest::class => MockResponse::fixture('get_recurring_invoice_service_fee_request'),
    ]);

    $dto = $this->sdk->send(new GetServiceFeeRequest(0, 501, 61, 7001, 8201))->dto();

    expect($dto)->toBeInstanceOf(ServiceFeeItem::class)
        ->and($dto->id)->toBe(8201)
        ->and($dto->serviceFee->id)->toBe(33)
        ->and($dto->serviceFee->name)->toBe('Maintenance visit')
        ->and($dto->billableStatus)->toBe(BillableStatus::Billable)
        ->and($dto->fitTime->multiplier)->toBe(1.0)
        ->and($dto->basePrice)->toBe(10.42)
        ->and($dto->salesTaxCode->rate)->toBe(20.0)
        ->and($dto->totals->priceInc)->toBe(22.5)
        ->and($dto->sellPrice->exDiscountExTax)->toBe(18.75)
        ->and($dto->total->qty)->toBe(1.0)
        ->and($dto->incomeAccount->id)->toBe(40);
});
