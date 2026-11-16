<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\BillableStatus;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Prebuilds\PrebuildItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Prebuilds\GetPrebuildRequest;

it('sends get recurring invoice prebuilds item request to correct endpoint', function () {
    MockClient::global([
        GetPrebuildRequest::class => MockResponse::fixture('get_recurring_invoice_prebuild_request'),
    ]);

    $request = new GetPrebuildRequest(0, 501, 61, 7001, 8501);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/prebuilds/8501');
});

it('parses get recurring invoice prebuilds item response correctly', function () {
    MockClient::global([
        GetPrebuildRequest::class => MockResponse::fixture('get_recurring_invoice_prebuild_request'),
    ]);

    $dto = $this->sdk->send(new GetPrebuildRequest(0, 501, 61, 7001, 8501))->dto();

    expect($dto)->toBeInstanceOf(PrebuildItem::class)
        ->and($dto->id)->toBe(8501)
        ->and($dto->prebuild->id)->toBe(310)
        ->and($dto->prebuild->partNo)->toBe('PB-QOLSYS')
        ->and($dto->billableStatus)->toBe(BillableStatus::Billable)
        ->and($dto->estimatedTime)->toBe(0.5)
        ->and($dto->basePrice)->toBe(12.0)
        ->and($dto->totals->estimatedTime)->toBe(0.0)
        ->and($dto->sellPrice->exDiscountIncTax)->toBe(22.5)
        ->and($dto->incomeAccount->id)->toBe(40);
});
