<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Info;

use Saloon\Http\Response;

final readonly class Info
{
    public function __construct(
        public string $version,
        public string $country,
        public bool $maintenancePlanner,
        public bool $multiCompany,
        public bool $sharedCatalog,
        public bool $sharedStock,
        public bool $sharedClients,
        public bool $sharedSetup,
        public bool $sharedDefaults,
        public bool $sharedAccountsIntegration,
        public bool $sharedVoip,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return new self(
            version: $data['Version'],
            country: $data['Country'],
            maintenancePlanner: $data['MaintenancePlanner'],
            multiCompany: $data['MultiCompany'],
            sharedCatalog: $data['SharedCatalog'],
            sharedStock: $data['SharedStock'],
            sharedClients: $data['SharedClients'],
            sharedSetup: $data['SharedSetup'],
            sharedDefaults: $data['SharedDefaults'],
            sharedAccountsIntegration: $data['SharedAccountsIntegration'],
            sharedVoip: $data['SharedVoip'],
        );
    }
}
