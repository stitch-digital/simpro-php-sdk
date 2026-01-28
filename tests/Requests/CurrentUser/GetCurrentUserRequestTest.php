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
        ->and($dto->id)->toBe(42)
        ->and($dto->username)->toBe('jdoe')
        ->and($dto->email)->toBe('john.doe@example.com')
        ->and($dto->givenName)->toBe('John')
        ->and($dto->familyName)->toBe('Doe')
        ->and($dto->displayName)->toBe('John Doe')
        ->and($dto->companies)->toHaveCount(2)
        ->and($dto->companies[0])->toBeInstanceOf(Reference::class)
        ->and($dto->companies[0]->id)->toBe(1)
        ->and($dto->companies[0]->name)->toBe('Main Company');
});

it('can access current user via resource', function () {
    MockClient::global([
        GetCurrentUserRequest::class => MockResponse::fixture('get_current_user_request'),
    ]);

    $user = $this->sdk->currentUser()->get();

    expect($user)->toBeInstanceOf(CurrentUser::class)
        ->and($user->fullName())->toBe('John Doe');
});
