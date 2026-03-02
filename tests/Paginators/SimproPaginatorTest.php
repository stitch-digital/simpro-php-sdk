<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Jobs\ListJobsRequest;

it('starts pagination at page 1', function () {
    $pages = [];

    MockClient::global([
        ListJobsRequest::class => function ($request) use (&$pages) {
            $page = $request->query()->get('page');
            $pages[] = $page;

            $isLastPage = count($pages) >= 3;

            return new MockResponse(
                body: json_encode([
                    ['ID' => count($pages), 'Description' => "Job on page {$page}"],
                ]),
                status: 200,
                headers: [
                    'Content-Type' => 'application/json',
                    'Result-Total' => '3',
                    'Result-Pages' => '3',
                ],
            );
        },
    ]);

    $items = $this->sdk->jobs(0)
        ->list()
        ->getPaginator()
        ->setPerPageLimit(1)
        ->collect()
        ->all();

    expect($pages)->toBe([1, 2, 3])
        ->and($items)->toHaveCount(3);
});

it('fetches all pages including the last page', function () {
    $requestCount = 0;

    MockClient::global([
        ListJobsRequest::class => function ($request) use (&$requestCount) {
            $requestCount++;
            $page = $request->query()->get('page');

            // 3 pages: pages 1 and 2 have 2 items, page 3 has 1 item
            $items = match ($page) {
                1 => [
                    ['ID' => 1, 'Description' => 'Job 1'],
                    ['ID' => 2, 'Description' => 'Job 2'],
                ],
                2 => [
                    ['ID' => 3, 'Description' => 'Job 3'],
                    ['ID' => 4, 'Description' => 'Job 4'],
                ],
                3 => [
                    ['ID' => 5, 'Description' => 'Job 5'],
                ],
                default => [],
            };

            return new MockResponse(
                body: json_encode($items),
                status: 200,
                headers: [
                    'Content-Type' => 'application/json',
                    'Result-Total' => '5',
                    'Result-Pages' => '3',
                ],
            );
        },
    ]);

    $items = $this->sdk->jobs(0)
        ->list()
        ->getPaginator()
        ->setPerPageLimit(2)
        ->collect()
        ->all();

    expect($requestCount)->toBe(3)
        ->and($items)->toHaveCount(5)
        ->and($items[4]->id)->toBe(5);
});
