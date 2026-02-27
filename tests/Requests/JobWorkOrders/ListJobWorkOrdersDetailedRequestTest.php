<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;
use Simpro\PhpSdk\Simpro\Data\JobWorkOrders\JobWorkOrderBlock;
use Simpro\PhpSdk\Simpro\Data\JobWorkOrders\JobWorkOrderDetailed;
use Simpro\PhpSdk\Simpro\Data\JobWorkOrders\JobWorkOrderProject;
use Simpro\PhpSdk\Simpro\Requests\JobWorkOrders\ListJobWorkOrdersDetailedRequest;

it('sends list job work orders detailed request to correct endpoint', function () {
    MockClient::global([
        ListJobWorkOrdersDetailedRequest::class => MockResponse::fixture('list_job_work_orders_detailed_request'),
    ]);

    $request = new ListJobWorkOrdersDetailedRequest(companyId: 0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('includes columns query parameter', function () {
    MockClient::global([
        ListJobWorkOrdersDetailedRequest::class => MockResponse::fixture('list_job_work_orders_detailed_request'),
    ]);

    $request = new ListJobWorkOrdersDetailedRequest(companyId: 0);
    $this->sdk->send($request);

    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('Blocks')
        ->and($query['columns'])->toContain('CustomFields')
        ->and($query['columns'])->toContain('Project');
});

it('parses list job work orders detailed response correctly', function () {
    MockClient::global([
        ListJobWorkOrdersDetailedRequest::class => MockResponse::fixture('list_job_work_orders_detailed_request'),
    ]);

    $request = new ListJobWorkOrdersDetailedRequest(companyId: 0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(JobWorkOrderDetailed::class)
        ->and($dto[0]->id)->toBe(501)
        ->and($dto[0]->staff)->toBeInstanceOf(StaffReference::class)
        ->and($dto[0]->staff->id)->toBe(10)
        ->and($dto[0]->staff->name)->toBe('John Smith')
        ->and($dto[0]->workOrderDate)->toBe('2026-02-20')
        ->and($dto[0]->project)->toBeInstanceOf(JobWorkOrderProject::class)
        ->and($dto[0]->project->id)->toBe(1001)
        ->and($dto[0]->project->name)->toBe('Office Fitout')
        ->and($dto[0]->descriptionNotes)->toBe('Install new electrical wiring in meeting rooms')
        ->and($dto[0]->materialNotes)->toBe('Use CAT6 cables only')
        ->and($dto[0]->approved)->toBeTrue()
        ->and($dto[0]->materials)->toHaveCount(1)
        ->and($dto[0]->blocks)->toHaveCount(1)
        ->and($dto[0]->blocks[0])->toBeInstanceOf(JobWorkOrderBlock::class)
        ->and($dto[0]->blocks[0]->hrs)->toBe(4.5)
        ->and($dto[0]->blocks[0]->startTime)->toBe('08:00 AM')
        ->and($dto[0]->blocks[0]->iso8601StartTime)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->blocks[0]->endTime)->toBe('12:30 PM')
        ->and($dto[0]->blocks[0]->iso8601EndTime)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->blocks[0]->scheduleRate)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->blocks[0]->scheduleRate->id)->toBe(1)
        ->and($dto[0]->blocks[0]->scheduleRate->name)->toBe('Standard')
        ->and($dto[0]->scheduledHrs)->toBe(4.5)
        ->and($dto[0]->iso8601ScheduledStartTime)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->iso8601ScheduledEndTime)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->dateModified)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->customFields)->toHaveCount(1)
        ->and($dto[0]->customFields[0])->toBeInstanceOf(CustomField::class)
        ->and($dto[0]->customFields[0]->name)->toBe('Priority')
        ->and($dto[0]->customFields[0]->value)->toBe('High')
        ->and($dto[1])->toBeInstanceOf(JobWorkOrderDetailed::class)
        ->and($dto[1]->id)->toBe(502)
        ->and($dto[1]->approved)->toBeFalse()
        ->and($dto[1]->blocks)->toBeEmpty()
        ->and($dto[1]->customFields)->toBeEmpty();
});
