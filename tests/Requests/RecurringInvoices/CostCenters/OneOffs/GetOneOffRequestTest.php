<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\BillableStatus;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\OneOffs\OneOffItem;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\OneOffs\OneOffType;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\OneOffs\GetOneOffRequest;

it('sends get recurring invoice oneOffs item request to correct endpoint', function () {
    MockClient::global([
        GetOneOffRequest::class => MockResponse::fixture('get_recurring_invoice_one_off_request'),
    ]);

    $request = new GetOneOffRequest(0, 501, 61, 7001, 8101);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/oneOffs/8101');
});

it('parses get recurring invoice oneOffs item response correctly', function () {
    MockClient::global([
        GetOneOffRequest::class => MockResponse::fixture('get_recurring_invoice_one_off_request'),
    ]);

    $dto = $this->sdk->send(new GetOneOffRequest(0, 501, 61, 7001, 8101))->dto();

    expect($dto)->toBeInstanceOf(OneOffItem::class)
        ->and($dto->id)->toBe(8101)
        ->and($dto->type)->toBe(OneOffType::Material)
        ->and($dto->billableStatus)->toBe(BillableStatus::Billable)
        ->and($dto->description)->toBe('Qolsys subscription')
        ->and($dto->estimatedCost)->toBe(12.0)
        ->and($dto->markup)->toBe(56.25)
        ->and($dto->vendor)->toBeNull()
        ->and($dto->salesTaxCode->code)->toBe('VAT20')
        ->and($dto->actualCostDate)->toBeNull()
        ->and($dto->sellPrice->exTax)->toBe(18.75)
        ->and($dto->total->qty)->toBe(1.0)
        ->and($dto->total->amount->incTax)->toBe(22.5)
        ->and($dto->incomeAccount->number)->toBe('4010')
        ->and($dto->dateModified->format('Y-m-d'))->toBe('2026-11-16');
});
