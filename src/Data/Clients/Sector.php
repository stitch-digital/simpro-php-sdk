<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Clients;

enum Sector: string
{
    case AccommodationAndFoodServices = 'Accommodation and Food Services';
    case AgricultureForestryAndFishing = 'Agriculture, Forestry and Fishing';
    case ArtsAndRecreationServices = 'Arts and Recreation Services';
    case Construction = 'Construction';
    case EducationAndTraining = 'Education and Training';
    case ElectricityGasWaterAndWasteServices = 'Electricity, Gas, Water and Waste Services';
    case FinancialAndInsuranceServices = 'Financial and Insurance Services';
    case FireAndSafetyServices = 'Fire and Safety Services';
    case HealthCareAndSocialAssistance = 'Health Care and Social Assistance';
    case InformationMediaAndTelecommunications = 'Information Media and Telecommunications';
    case Manufacturing = 'Manufacturing';
    case Mining = 'Mining';
    case ProfessionalScientificAndTechnicalServices = 'Professional, Scientific and Technical Services';
    case PublicAdministration = 'Public Administration';
    case PropertyManagementResidential = 'Property Management (Residential)';
    case PropertyManagementCommercial = 'Property Management (Commercial)';
    case RetailTrade = 'Retail Trade';
    case RealEstateInvestmentTrust = 'Real Estate Investment Trust';
    case TransportPostalAndWarehousing = 'Transport, Postal and Warehousing';
    case WholesaleTrade = 'Wholesale Trade';
    case OtherServices = 'Other Services';

    /**
     * Get the display name for the sector.
     */
    public function displayName(): string
    {
        return match ($this) {
            self::Construction => 'Construction and Installation',
            self::PublicAdministration => 'Public Administration (Local, State or Federal Government)',
            self::RealEstateInvestmentTrust => 'Real Estate Investment Trust (REIT)',
            default => $this->value,
        };
    }

    /**
     * Try to create a Sector enum from a string value.
     */
    public static function tryFromString(string $value): ?self
    {
        return self::tryFrom($value);
    }
}
