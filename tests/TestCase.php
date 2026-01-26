<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Tests;

use Saloon\Http\Faking\MockClient;
use Saloon\MockConfig;
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected SimproApiKeyConnector $sdk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sdk = new SimproApiKeyConnector(
            baseUrl: 'https://api.simpro.test',
            apiKey: 'test-api-key'
        );

        MockConfig::setFixturePath(__DIR__.'/Fixtures/Saloon');

        // Reset mock client before each test
        MockClient::destroyGlobal();
    }

    protected function tearDown(): void
    {
        // Clean up mock client after each test
        MockClient::destroyGlobal();

        parent::tearDown();
    }
}
