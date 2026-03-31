<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\UpdateContractorJobRequest;

it('sends update job contractor job request to correct endpoint', function () {
    MockClient::global([
        UpdateContractorJobRequest::class => MockResponse::fixture('update_job_contractor_job_request'),
    ]);

    $request = new UpdateContractorJobRequest(0, 414786, 15311, 15615, 1630, [
        'Description' => 'Updated description',
    ]);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});
