<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\CreateContractorJobRequest;

it('sends create job contractor job request to correct endpoint', function () {
    MockClient::global([
        CreateContractorJobRequest::class => MockResponse::fixture('create_job_contractor_job_request'),
    ]);

    $request = new CreateContractorJobRequest(0, 414786, 15311, 15615, [
        'Contractor' => 327,
        'Description' => 'Test contractor job',
    ]);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(201);
});

it('returns created job contractor job ID', function () {
    MockClient::global([
        CreateContractorJobRequest::class => MockResponse::fixture('create_job_contractor_job_request'),
    ]);

    $request = new CreateContractorJobRequest(0, 414786, 15311, 15615, [
        'Contractor' => 327,
    ]);
    $response = $this->sdk->send($request);
    $id = $response->dto();

    expect($id)->toBe(1631);
});
