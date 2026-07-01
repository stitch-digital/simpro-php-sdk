<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Quotes\ConvertQuoteRequest;

it('converts a quote to a job and returns the job id', function () {
    MockClient::global([
        ConvertQuoteRequest::class => MockResponse::fixture('convert_quote_request'),
    ]);

    $jobId = $this->sdk->quotes(0)->convert(316228);

    expect($jobId)->toBe(480999);
});
