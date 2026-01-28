<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Invoices\InvoiceListItem;
use Simpro\PhpSdk\Simpro\Requests\Invoices\ListInvoicesRequest;

it('sends list invoices request to correct endpoint', function () {
    MockClient::global([
        ListInvoicesRequest::class => MockResponse::fixture('list_invoices_request'),
    ]);

    $request = new ListInvoicesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list invoices response correctly', function () {
    MockClient::global([
        ListInvoicesRequest::class => MockResponse::fixture('list_invoices_request'),
    ]);

    $request = new ListInvoicesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(InvoiceListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->invoiceNo)->toBe('INV-001')
        ->and($dto[0]->status)->toBe('Unpaid')
        ->and($dto[1])->toBeInstanceOf(InvoiceListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->invoiceNo)->toBe('INV-002')
        ->and($dto[1]->status)->toBe('Paid');
});
