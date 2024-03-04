<?php

declare(strict_types=1);

namespace oym\craft\geoaddress\models;

use craft\base\Model;

class GeoAddressModel extends Model
{
    public ?string $name = null;

    public ?string $street = null;

    public ?string $city = null;

    public ?string $zip = null;

    /** @deprecated Not in use anymore, but cant be removed as it corrupts existing models */
    public ?string $state = null;

    public ?string $country = null;

    public ?string $countryName = null;

    public ?string $countryCode = null;

    public ?float $lat = null;

    public ?float $lng = null;

    public ?string $formattedAddress = null;

    public ?float $filterDistance = null;

    public function getAddress(): string
    {
        $address = array_filter([
            $this->street,
            $this->city,
            $this->zip,
            $this->country
        ]);

        return implode(' ', $address);
    }

    public function rules(): array
    {
        return [
            ['name', 'string'],
            ['street', 'string'],
            ['city', 'string'],
            ['zip', 'string'],
            ['state', 'string'],
            ['country', 'string'],
            ['countryName', 'string'],
            ['countryCode', 'string'],
            ['lat', 'number'],
            ['lng', 'number'],
            ['formattedAddress', 'string']
        ];
    }
}
