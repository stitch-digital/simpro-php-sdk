<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\BillableStatus;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ServiceFees\ServiceFeeListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ServiceFees\ListServiceFeesRequest;

it('sends list recurring invoice serviceFees request to correct endpoint', function () {
    MockClient::global([
        ListServiceFeesRequest::class => MockResponse::fixture('list_recurring_invoice_service_fees_request'),
    ]);

    $request = new ListServiceFeesRequest(0, 501, 61, 7001);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/serviceFees/');
});

it('parses list recurring invoice serviceFees response correctly', function () {
    MockClient::global([
        ListServiceFeesRequest::class => MockResponse::fixture('list_recurring_invoice_service_fees_request'),
    ]);

    $dto = $this->sdk->send(new ListServiceFeesRequest(0, 501, 61, 7001))->dto();

    expect($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(ServiceFeeListItem::class)
        ->and($dto[0]->id)->toBe(8201)
        ->and($dto[0]->serviceFee->id)->toBe(33)
        ->and($dto[0]->billableStatus)->toBe(BillableStatus::Billable)
        ->and($dto[0]->total->amount->incTax)->toBe(22.5);
});
