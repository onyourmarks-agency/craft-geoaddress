<?php

namespace TDE\GeoAddress\models;

use Craft;
use craft\base\Model;

/**
 * Class GeoAddressModel
 *
 * @package TDE\GeoAddress\models
 */
class GeoAddressModel extends Model
{
    /**
     * @var string
     */
    public $name;

	/**
	 * @var string
	 */
	public $street;

	/**
	 * @var string
	 */
	public $city;

	/**
	 * @var string
	 */
	public $zip;

    /**
     * @var string
     * @deprecated Not in use anymore, but cant be removed as it corrupts existing models
     */
    public $state;

    /**
     * @var string
     */
	public $country;

	/**
	 * @var string
	 */
	public $countryName;

	/**
	 * @var string
	 */
	public $countryCode;

	/**
	 * @var float
	 */
	public $lat;

	/**
	 * @var float
	 */
	public $lng;

	/**
	 * @var string
	 */
	public $formattedAddress;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
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

    /**
     * @return string
     */
    public function getAddress(): string
    {
        $address = '';

        if ($this->street) {
            $address .= ($address ? ' ' : '') . $this->street;
        }

        if ($this->city) {
            $address .= ($address ? ' ' : '') . $this->city;
        }

        if ($this->zip) {
            $address .= ($address ? ' ' : '') . $this->zip;
        }

        if ($this->country) {
            $address .= ($address ? ' ' : '') . $this->country;
        }

        return $address;
    }
}
