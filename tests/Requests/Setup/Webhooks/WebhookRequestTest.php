<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\Webhook;
use Simpro\PhpSdk\Simpro\Data\Setup\WebhookListItem;
use Simpro\PhpSdk\Simpro\Requests\Setup\Webhooks\CreateWebhookRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Webhooks\DeleteWebhookRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Webhooks\GetWebhookRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Webhooks\ListWebhooksRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Webhooks\UpdateWebhookRequest;

it('sends list webhooks request to correct endpoint', function () {
    MockClient::global([
        ListWebhooksRequest::class => MockResponse::fixture('list_webhooks_request'),
    ]);

    $request = new ListWebhooksRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list webhooks response correctly', function () {
    MockClient::global([
        ListWebhooksRequest::class => MockResponse::fixture('list_webhooks_request'),
    ]);

    $request = new ListWebhooksRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(WebhookListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('Job Update Trigger')
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->name)->toBe('Invoice Created Trigger');
});

it('sends get webhook request to correct endpoint', function () {
    MockClient::global([
        GetWebhookRequest::class => MockResponse::fixture('get_webhook_request'),
    ]);

    $request = new GetWebhookRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get webhook response correctly', function () {
    MockClient::global([
        GetWebhookRequest::class => MockResponse::fixture('get_webhook_request'),
    ]);

    $request = new GetWebhookRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Webhook::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('Job Update Trigger')
        ->and($dto->callbackUrl)->toBe('https://example.com/webhook')
        ->and($dto->secret)->toBe('secret123')
        ->and($dto->email)->toBe('admin@example.com')
        ->and($dto->description)->toBe('Triggers when a job is updated')
        ->and($dto->events)->toBe(['job.created', 'job.updated', 'job.deleted'])
        ->and($dto->status)->toBe('Enabled')
        ->and($dto->dateCreated)->toBeInstanceOf(DateTimeImmutable::class);
});

it('sends create webhook request and returns id', function () {
    MockClient::global([
        CreateWebhookRequest::class => MockResponse::fixture('create_webhook_request'),
    ]);

    $request = new CreateWebhookRequest(0, [
        'Name' => 'New Webhook',
        'CallbackURL' => 'https://example.com/new-webhook',
        'Events' => ['job.created'],
    ]);
    $response = $this->sdk->send($request);
    $id = $response->dto();

    expect($response->status())->toBe(201)
        ->and($id)->toBe(3);
});

it('sends update webhook request', function () {
    MockClient::global([
        UpdateWebhookRequest::class => MockResponse::fixture('update_webhook_request'),
    ]);

    $request = new UpdateWebhookRequest(0, 1, [
        'Name' => 'Updated Webhook',
    ]);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('sends delete webhook request', function () {
    MockClient::global([
        DeleteWebhookRequest::class => MockResponse::fixture('delete_webhook_request'),
    ]);

    $request = new DeleteWebhookRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('can access webhooks via setup resource', function () {
    MockClient::global([
        ListWebhooksRequest::class => MockResponse::fixture('list_webhooks_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->webhooks()->list();

    expect($queryBuilder)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Query\QueryBuilder::class);
});

it('can get webhook via setup resource', function () {
    MockClient::global([
        GetWebhookRequest::class => MockResponse::fixture('get_webhook_request'),
    ]);

    $webhook = $this->sdk->setup(0)->webhooks()->get(1);

    expect($webhook)->toBeInstanceOf(Webhook::class)
        ->and($webhook->id)->toBe(1);
});

it('can create webhook via setup resource', function () {
    MockClient::global([
        CreateWebhookRequest::class => MockResponse::fixture('create_webhook_request'),
    ]);

    $id = $this->sdk->setup(0)->webhooks()->create([
        'Name' => 'New Webhook',
        'CallbackURL' => 'https://example.com/new-webhook',
        'Events' => ['job.created'],
    ]);

    expect($id)->toBe(3);
});

it('can update webhook via setup resource', function () {
    MockClient::global([
        UpdateWebhookRequest::class => MockResponse::fixture('update_webhook_request'),
    ]);

    $response = $this->sdk->setup(0)->webhooks()->update(1, [
        'Name' => 'Updated Webhook',
    ]);

    expect($response->status())->toBe(204);
});

it('can delete webhook via setup resource', function () {
    MockClient::global([
        DeleteWebhookRequest::class => MockResponse::fixture('delete_webhook_request'),
    ]);

    $response = $this->sdk->setup(0)->webhooks()->delete(1);

    expect($response->status())->toBe(204);
});
