<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Tasks\TaskAssignee;
use Simpro\PhpSdk\Simpro\Data\Tasks\TaskAssociated;
use Simpro\PhpSdk\Simpro\Data\Tasks\TaskAssociatedCustomer;
use Simpro\PhpSdk\Simpro\Data\Tasks\TaskAssociatedJob;
use Simpro\PhpSdk\Simpro\Data\Tasks\TaskDuration;
use Simpro\PhpSdk\Simpro\Data\Tasks\TaskListDetailedItem;
use Simpro\PhpSdk\Simpro\Requests\Tasks\ListTasksDetailedRequest;

it('sends list tasks detailed request to correct endpoint', function () {
    MockClient::global([
        ListTasksDetailedRequest::class => MockResponse::fixture('list_tasks_detailed_request'),
    ]);

    $request = new ListTasksDetailedRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/tasks/');
});

it('includes all columns in the request query', function () {
    MockClient::global([
        ListTasksDetailedRequest::class => MockResponse::fixture('list_tasks_detailed_request'),
    ]);

    $request = new ListTasksDetailedRequest(0);

    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('ID')
        ->and($query['columns'])->toContain('Subject')
        ->and($query['columns'])->toContain('CreatedBy')
        ->and($query['columns'])->toContain('AssignedTo')
        ->and($query['columns'])->toContain('Assignees')
        ->and($query['columns'])->toContain('AssignedToCustomer')
        ->and($query['columns'])->toContain('CompletedBy')
        ->and($query['columns'])->toContain('Associated')
        ->and($query['columns'])->toContain('IsBillable')
        ->and($query['columns'])->toContain('ShowOnWorkOrder')
        ->and($query['columns'])->toContain('EmailNotifications')
        ->and($query['columns'])->toContain('Description')
        ->and($query['columns'])->toContain('StartDate')
        ->and($query['columns'])->toContain('DueDate')
        ->and($query['columns'])->toContain('CompletedDate')
        ->and($query['columns'])->toContain('Notes')
        ->and($query['columns'])->toContain('Status')
        ->and($query['columns'])->toContain('Priority')
        ->and($query['columns'])->toContain('Category')
        ->and($query['columns'])->toContain('Estimated')
        ->and($query['columns'])->toContain('Actual')
        ->and($query['columns'])->toContain('ParentTask')
        ->and($query['columns'])->toContain('SubTasks')
        ->and($query['columns'])->toContain('CustomFields')
        ->and($query['columns'])->toContain('PercentComplete')
        ->and($query['columns'])->toContain('DateModified');
});

it('parses list tasks detailed response correctly', function () {
    MockClient::global([
        ListTasksDetailedRequest::class => MockResponse::fixture('list_tasks_detailed_request'),
    ]);

    $request = new ListTasksDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(TaskListDetailedItem::class)
        ->and($dto[0]->id)->toBe(79527)
        ->and($dto[0]->subject)->toBe('Please Order and Send Tom Burchall 23.03')
        ->and($dto[0]->status)->toBe('Pending')
        ->and($dto[0]->priority)->toBe('High')
        ->and($dto[0]->assignedToCustomer)->toBeFalse()
        ->and($dto[0]->isBillable)->toBeFalse()
        ->and($dto[0]->showOnWorkOrder)->toBeFalse()
        ->and($dto[0]->emailNotifications)->toBeTrue();
});

it('parses created by correctly', function () {
    MockClient::global([
        ListTasksDetailedRequest::class => MockResponse::fixture('list_tasks_detailed_request'),
    ]);

    $request = new ListTasksDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->createdBy)->toBeInstanceOf(TaskAssignee::class)
        ->and($dto[0]->createdBy->id)->toBe(1055)
        ->and($dto[0]->createdBy->name)->toBe('Tiani Driver')
        ->and($dto[0]->createdBy->type)->toBe('employee')
        ->and($dto[0]->createdBy->typeId)->toBe(1055);
});

it('parses associated job and customer correctly', function () {
    MockClient::global([
        ListTasksDetailedRequest::class => MockResponse::fixture('list_tasks_detailed_request'),
    ]);

    $request = new ListTasksDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->associated)->toBeInstanceOf(TaskAssociated::class)
        ->and($dto[0]->associated->job)->toBeInstanceOf(TaskAssociatedJob::class)
        ->and($dto[0]->associated->job->id)->toBe(470624)
        ->and($dto[0]->associated->job->costCenter)->toBeNull()
        ->and($dto[0]->associated->customer)->toBeInstanceOf(TaskAssociatedCustomer::class)
        ->and($dto[0]->associated->customer->id)->toBe(6468)
        ->and($dto[0]->associated->customer->companyName)->toBe('OCS M&E Services LTD - Southwark');
});

it('parses associated site and empty references correctly', function () {
    MockClient::global([
        ListTasksDetailedRequest::class => MockResponse::fixture('list_tasks_detailed_request'),
    ]);

    $request = new ListTasksDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->associated->site)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->associated->site->id)->toBe(22875)
        ->and($dto[0]->associated->site->name)->toBe('Southwark Registry Office')
        ->and($dto[0]->associated->quote)->toBeNull()
        ->and($dto[0]->associated->contact)->toBeNull();
});

it('parses estimated and actual duration correctly', function () {
    MockClient::global([
        ListTasksDetailedRequest::class => MockResponse::fixture('list_tasks_detailed_request'),
    ]);

    $request = new ListTasksDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->estimated)->toBeInstanceOf(TaskDuration::class)
        ->and($dto[0]->estimated->hours)->toBe(0)
        ->and($dto[0]->estimated->minutes)->toBe(0)
        ->and($dto[0]->estimated->seconds)->toBe(0)
        ->and($dto[0]->actual)->toBeInstanceOf(TaskDuration::class)
        ->and($dto[0]->actual->hours)->toBe(0);
});

it('parses empty category and parent task as null', function () {
    MockClient::global([
        ListTasksDetailedRequest::class => MockResponse::fixture('list_tasks_detailed_request'),
    ]);

    $request = new ListTasksDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->category)->toBeNull()
        ->and($dto[0]->parentTask)->toBeNull()
        ->and($dto[0]->subTasks)->toBeArray()
        ->and($dto[0]->subTasks)->toBeEmpty();
});

it('parses custom fields correctly', function () {
    MockClient::global([
        ListTasksDetailedRequest::class => MockResponse::fixture('list_tasks_detailed_request'),
    ]);

    $request = new ListTasksDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->customFields)->toBeArray()
        ->and($dto[0]->customFields)->toHaveCount(1)
        ->and($dto[0]->customFields[0])->toBeInstanceOf(CustomField::class)
        ->and($dto[0]->customFields[0]->id)->toBe(138)
        ->and($dto[0]->customFields[0]->name)->toBe('Autogenerate Approved Certificate?')
        ->and($dto[0]->customFields[0]->type)->toBe('List')
        ->and($dto[0]->customFields[0]->value)->toBeNull();
});

it('parses date modified correctly', function () {
    MockClient::global([
        ListTasksDetailedRequest::class => MockResponse::fixture('list_tasks_detailed_request'),
    ]);

    $request = new ListTasksDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->dateModified)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->dateModified->format('Y-m-d'))->toBe('2026-03-12');
});

it('parses null date fields correctly', function () {
    MockClient::global([
        ListTasksDetailedRequest::class => MockResponse::fixture('list_tasks_detailed_request'),
    ]);

    $request = new ListTasksDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->startDate)->toBeNull()
        ->and($dto[0]->dueDate)->toBeNull()
        ->and($dto[0]->completedDate)->toBeNull()
        ->and($dto[0]->completedBy)->toBeNull();
});
