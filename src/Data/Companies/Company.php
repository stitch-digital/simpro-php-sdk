<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Companies;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class Company
{
    public function __construct(
        public int $id,
        public string $name,
        public string $phone,
        public string $fax,
        public string $email,
        public Address $address,
        public Address $billingAddress,
        public string $ein,
        public string $companyNo,
        public string $licence,
        public string $website,
        public Banking $banking,
        public string $cisCertNo,
        public string $employerTaxRefNo,
        public string $timezone,
        public string $timezoneOffset,
        public DefaultLanguage $defaultLanguage,
        public bool $template,
        public ?string $multiCompanyLabel,
        public ?string $multiCompanyColor,
        public string $currency,
        public string $country,
        public string $taxName,
        public string $uiDateFormat,
        public string $uiTimeFormat,
        public ScheduleFormat $scheduleFormat,
        public bool $singleCostCenterMode,
        public DateTimeImmutable $dateModified,
        public ?DefaultCostCenter $defaultCostCenter,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'],
            phone: $data['Phone'] ?? '',
            fax: $data['Fax'] ?? '',
            email: $data['Email'] ?? '',
            address: Address::fromArray($data['Address'] ?? []),
            billingAddress: Address::fromArray($data['BillingAddress'] ?? []),
            ein: $data['EIN'] ?? '',
            companyNo: $data['CompanyNo'] ?? '',
            licence: $data['Licence'] ?? '',
            website: $data['Website'] ?? '',
            banking: Banking::fromArray($data['Banking'] ?? []),
            cisCertNo: $data['CISCertNo'] ?? '',
            employerTaxRefNo: $data['EmployerTaxRefNo'] ?? '',
            timezone: $data['Timezone'] ?? '',
            timezoneOffset: $data['TimezoneOffset'] ?? '',
            defaultLanguage: DefaultLanguage::from($data['DefaultLanguage']),
            template: $data['Template'] ?? false,
            multiCompanyLabel: $data['MultiCompanyLabel'] ?? null,
            multiCompanyColor: $data['MultiCompanyColor'] ?? null,
            currency: $data['Currency'] ?? '',
            country: $data['Country'] ?? '',
            taxName: $data['TaxName'] ?? '',
            uiDateFormat: $data['UIDateFormat'] ?? '',
            uiTimeFormat: $data['UITimeFormat'] ?? '',
            scheduleFormat: ScheduleFormat::from($data['ScheduleFormat']),
            singleCostCenterMode: $data['SingleCostCenterMode'] ?? false,
            dateModified: new DateTimeImmutable($data['DateModified']),
            defaultCostCenter: isset($data['DefaultCostCenter'])
                ? DefaultCostCenter::fromArray($data['DefaultCostCenter'])
                : null,
        );
    }
}
