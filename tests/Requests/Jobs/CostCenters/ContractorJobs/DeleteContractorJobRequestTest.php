<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\DeleteContractorJobRequest;

it('sends delete job contractor job request to correct endpoint', function () {
    MockClient::global([
        DeleteContractorJobRequest::class => MockResponse::fixture('delete_job_contractor_job_request'),
    ]);

    $request = new DeleteContractorJobRequest(0, 414786, 15311, 15615, 1630);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});
