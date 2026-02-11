<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\CurrentUser\CurrentUser;
use Simpro\PhpSdk\Simpro\Requests\CurrentUser\GetCurrentUserRequest;

it('sends get current user request to correct endpoint', function () {
    MockClient::global([
        GetCurrentUserRequest::class => MockResponse::fixture('get_current_user_request'),
    ]);

    $request = new GetCurrentUserRequest;
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get current user response correctly', function () {
    MockClient::global([
        GetCurrentUserRequest::class => MockResponse::fixture('get_current_user_request'),
    ]);

    $request = new GetCurrentUserRequest;
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(CurrentUser::class)
        ->and($dto->id)->toBe(705)
        ->and($dto->name)->toBe('Seen Services - Automation Platform')
        ->and($dto->type)->toBe('employee')
        ->and($dto->typeId)->toBe(705)
        ->and($dto->preferredLanguage)->toBe('en_GB')
        ->and($dto->accessibleCompanies)->toHaveCount(1)
        ->and($dto->accessibleCompanies[0])->toBeInstanceOf(Reference::class)
        ->and($dto->accessibleCompanies[0]->id)->toBe(0)
        ->and($dto->accessibleCompanies[0]->name)->toBe('Seen Services');
});

it('can access current user via resource', function () {
    MockClient::global([
        GetCurrentUserRequest::class => MockResponse::fixture('get_current_user_request'),
    ]);

    $user = $this->sdk->currentUser()->get();

    expect($user)->toBeInstanceOf(CurrentUser::class)
        ->and($user->name)->toBe('Seen Services - Automation Platform');
});
