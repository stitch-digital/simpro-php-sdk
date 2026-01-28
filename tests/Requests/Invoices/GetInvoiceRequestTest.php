<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Invoices\Invoice;
use Simpro\PhpSdk\Simpro\Requests\Invoices\GetInvoiceRequest;

it('sends get invoice request to correct endpoint', function () {
    MockClient::global([
        GetInvoiceRequest::class => MockResponse::fixture('get_invoice_request'),
    ]);

    $request = new GetInvoiceRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get invoice response correctly', function () {
    MockClient::global([
        GetInvoiceRequest::class => MockResponse::fixture('get_invoice_request'),
    ]);

    $request = new GetInvoiceRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Invoice::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->invoiceNo)->toBe('INV-001')
        ->and($dto->status)->toBe('Unpaid')
        ->and($dto->customer)->not->toBeNull()
        ->and($dto->customer->companyName)->toBe('Acme Corp')
        ->and($dto->site)->not->toBeNull()
        ->and($dto->site->id)->toBe(10)
        ->and($dto->totals)->not->toBeNull()
        ->and($dto->totals->totalIncTax)->toBe(5000.00)
        ->and($dto->totals->amountDue)->toBe(5000.00);
});
