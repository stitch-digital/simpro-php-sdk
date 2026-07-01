<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Quotes\ConvertQuoteRequest;

it('sends convert quote request to correct endpoint', function () {
    MockClient::global([
        ConvertQuoteRequest::class => MockResponse::fixture('convert_quote_request'),
    ]);

    $request = new ConvertQuoteRequest(0, 316228);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/quotes/316228/convert/');
});

it('parses convert quote response into created job id', function () {
    MockClient::global([
        ConvertQuoteRequest::class => MockResponse::fixture('convert_quote_request'),
    ]);

    $request = new ConvertQuoteRequest(0, 316228);
    $response = $this->sdk->send($request);

    expect($request->createDtoFromResponse($response))->toBe(480999);
});
