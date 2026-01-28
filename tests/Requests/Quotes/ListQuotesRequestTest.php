<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Quotes\QuoteListItem;
use Simpro\PhpSdk\Simpro\Requests\Quotes\ListQuotesRequest;

it('sends list quotes request to correct endpoint', function () {
    MockClient::global([
        ListQuotesRequest::class => MockResponse::fixture('list_quotes_request'),
    ]);

    $request = new ListQuotesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list quotes response correctly', function () {
    MockClient::global([
        ListQuotesRequest::class => MockResponse::fixture('list_quotes_request'),
    ]);

    $request = new ListQuotesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(QuoteListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->description)->toBe('Kitchen renovation quote')
        ->and($dto[0]->total->exTax)->toBe(15000.00)
        ->and($dto[0]->total->tax)->toBe(1500.00)
        ->and($dto[0]->total->incTax)->toBe(16500.00)
        ->and($dto[1])->toBeInstanceOf(QuoteListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->description)->toBe('Bathroom renovation quote');
});
