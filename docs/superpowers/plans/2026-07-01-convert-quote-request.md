# swift-orca — ConvertQuoteRequest Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add a `ConvertQuoteRequest` to swift-orca (`stitch-digital/simpro-php-sdk`) that posts to `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/convert/` and expose it on `QuoteResource::convert()`.

**Architecture:** Follow existing Saloon-based request pattern (see `CreateQuoteRequest`, `UpdateQuoteRequest`). Add matching resource-level method. Ship as a tagged release consumable from swift-podenco.

**Tech Stack:** PHP 8.2+, Saloon HTTP client, Pest test framework.

## Global Constraints

- All new PHP files start with `declare(strict_types=1);`.
- Namespace: `Simpro\PhpSdk\Simpro\Requests\Quotes` (requests) / `Simpro\PhpSdk\Simpro\Resources` (resources).
- All request classes are `final`.
- Constructor promotion with `private readonly` for injected params.
- Match existing naming: `ConvertQuoteRequest`, `convert()`.
- Pest test files named `<Class>Test.php` under `tests/Requests/Quotes/` and `tests/Resources/`.
- Test fixtures under `tests/Fixtures/Saloon/`.

---

### Task 1: Add ConvertQuoteRequest class + request test

**Files:**
- Create: `src/Requests/Quotes/ConvertQuoteRequest.php`
- Create: `tests/Requests/Quotes/ConvertQuoteRequestTest.php`
- Create: `tests/Fixtures/Saloon/convert_quote_request.json`

**Interfaces:**
- Consumes: nothing new — Saloon `Request` + `Method::POST`.
- Produces: `ConvertQuoteRequest(int $companyId, int|string $quoteId)` — sends `POST /api/v1.0/companies/{companyId}/quotes/{quoteId}/convert/`, returns `Saloon\Http\Response`. `createDtoFromResponse(Response): int` returns the created Job ID (`$data['ID']`).

- [ ] **Step 1: Add fixture for convert response**

Create `tests/Fixtures/Saloon/convert_quote_request.json`:

```json
{
    "statusCode": 200,
    "headers": {
        "Content-Type": "application/json"
    },
    "data": {
        "ID": 480999,
        "Href": "/api/v1.0/companies/0/jobs/480999"
    }
}
```

- [ ] **Step 2: Write failing test**

Create `tests/Requests/Quotes/ConvertQuoteRequestTest.php`:

```php
<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Quotes\ConvertQuoteRequest;

it('sends convert quote request to correct endpoint', function () {
    MockClient::global([
        ConvertQuoteRequest::class => MockResponse::fixture('convert_quote_request'),
    ]);

    $request = new ConvertQuoteRequest(0, 316228);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/quotes/316228/convert/');
});

it('parses convert quote response into created job id', function () {
    MockClient::global([
        ConvertQuoteRequest::class => MockResponse::fixture('convert_quote_request'),
    ]);

    $request = new ConvertQuoteRequest(0, 316228);
    $response = $this->sdk->send($request);

    expect($request->createDtoFromResponse($response))->toBe(480999);
});
```

- [ ] **Step 3: Run test — expect failure**

Run: `vendor/bin/pest tests/Requests/Quotes/ConvertQuoteRequestTest.php`
Expected: FAIL — `Class "Simpro\PhpSdk\Simpro\Requests\Quotes\ConvertQuoteRequest" not found`.

- [ ] **Step 4: Implement ConvertQuoteRequest**

Create `src/Requests/Quotes/ConvertQuoteRequest.php`:

```php
<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Quotes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class ConvertQuoteRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $quoteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/convert/";
    }

    public function createDtoFromResponse(Response $response): int
    {
        $data = $response->json();

        return (int) $data['ID'];
    }
}
```

- [ ] **Step 5: Run test — expect pass**

Run: `vendor/bin/pest tests/Requests/Quotes/ConvertQuoteRequestTest.php`
Expected: PASS (2 tests).

- [ ] **Step 6: Commit**

```bash
git add src/Requests/Quotes/ConvertQuoteRequest.php \
        tests/Requests/Quotes/ConvertQuoteRequestTest.php \
        tests/Fixtures/Saloon/convert_quote_request.json
git commit -m "feat(quotes): add ConvertQuoteRequest"
```

---

### Task 2: Expose convert() on QuoteResource + resource test

**Files:**
- Modify: `src/Resources/QuoteResource.php`
- Create: `tests/Resources/QuoteResourceConvertTest.php`

**Interfaces:**
- Consumes: `ConvertQuoteRequest(int $companyId, int|string $quoteId)` from Task 1.
- Produces: `QuoteResource::convert(int|string $quoteId): int` — sends the request and returns the created Job ID.

- [ ] **Step 1: Write failing test**

Create `tests/Resources/QuoteResourceConvertTest.php`:

```php
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
```

- [ ] **Step 2: Run test — expect failure**

Run: `vendor/bin/pest tests/Resources/QuoteResourceConvertTest.php`
Expected: FAIL — `Call to undefined method ... QuoteResource::convert()`.

- [ ] **Step 3: Add convert() to QuoteResource**

Modify `src/Resources/QuoteResource.php`:

1. Add import near existing quote request imports:

```php
use Simpro\PhpSdk\Simpro\Requests\Quotes\ConvertQuoteRequest;
```

2. Add method (place after `delete()`):

```php
    /**
     * Convert an approved quote to a job.
     *
     * @return int The ID of the created job
     */
    public function convert(int|string $quoteId): int
    {
        $request = new ConvertQuoteRequest($this->companyId, $quoteId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }
```

- [ ] **Step 4: Run tests — expect pass**

Run: `vendor/bin/pest tests/Resources/QuoteResourceConvertTest.php tests/Requests/Quotes/ConvertQuoteRequestTest.php`
Expected: PASS (3 tests).

- [ ] **Step 5: Run full suite**

Run: `vendor/bin/pest`
Expected: all green.

- [ ] **Step 6: Commit**

```bash
git add src/Resources/QuoteResource.php tests/Resources/QuoteResourceConvertTest.php
git commit -m "feat(quotes): expose QuoteResource::convert()"
```

---

### Task 3: Tag release

**Files:** none modified.

**Interfaces:**
- Consumes: prior tasks committed on `main` (or PR merged in).
- Produces: a git tag consumable by swift-podenco via `composer update stitch-digital/simpro-php-sdk`.

- [ ] **Step 1: Determine next semver tag**

Run: `git tag --sort=-v:refname | head -5`

Pick next MINOR bump (new feature, backwards-compatible). If current is e.g. `v1.4.3`, next is `v1.5.0`.

- [ ] **Step 2: Tag from latest commit on main**

Run: `git tag -a v1.5.0 -m "Add ConvertQuoteRequest + QuoteResource::convert()"`

Substitute the version from Step 1.

- [ ] **Step 3: Push tag**

Run: `git push origin v1.5.0`

- [ ] **Step 4: Verify Packagist picks it up**

Wait ~30s then check https://packagist.org/packages/stitch-digital/simpro-php-sdk — the new version should appear. If Packagist webhook not configured, trigger update manually via Packagist UI.

- [ ] **Step 5: Handoff note**

Record the new tag version in a message to the parent workspace (swift-podenco) so its plan can bump the composer constraint.

---

## Self-Review

**Spec coverage:** Section 7 of `swift-podenco/docs/superpowers/specs/2026-07-01-portal-quote-ppm-detection-design.md` requires `ConvertQuoteRequest`, `QuoteResource::convert()`, and a tagged release. All covered by Tasks 1, 2, 3.

**Note on `UpdateQuoteRequest`:** spec asks to "verify UpdateQuoteRequest accepts partial body `{"OrderNo": "..."}`". Confirmed by inspection — `UpdateQuoteRequest::defaultBody()` returns `$this->data` verbatim, no wrapping. No code change needed. Callers must pass `["OrderNo" => "..."]`.

**Placeholder scan:** no TBD/TODO/placeholder text found.

**Type consistency:** `ConvertQuoteRequest::__construct(int $companyId, int|string $quoteId)` matches signature used in Task 2 and matches sibling request classes.
