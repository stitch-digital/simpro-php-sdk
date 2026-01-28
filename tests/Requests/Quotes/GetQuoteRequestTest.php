<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Quotes\Quote;
use Simpro\PhpSdk\Simpro\Requests\Quotes\GetQuoteRequest;

it('sends get quote request to correct endpoint', function () {
    MockClient::global([
        GetQuoteRequest::class => MockResponse::fixture('get_quote_request'),
    ]);

    $request = new GetQuoteRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get quote response correctly', function () {
    MockClient::global([
        GetQuoteRequest::class => MockResponse::fixture('get_quote_request'),
    ]);

    $request = new GetQuoteRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Quote::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('Kitchen Quote')
        ->and($dto->status)->toBe('Approved')
        ->and($dto->stage)->toBe('Won')
        ->and($dto->site)->not->toBeNull()
        ->and($dto->site->id)->toBe(10)
        ->and($dto->customer)->not->toBeNull()
        ->and($dto->customer->companyName)->toBe('Acme Corp')
        ->and($dto->totals)->not->toBeNull()
        ->and($dto->totals->totalIncTax)->toBe(15000.00);
});
