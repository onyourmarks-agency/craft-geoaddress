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
	public $street;

	/**
	 * @var string
	 */
	public $city;

	/**
	 * @var string
	 */
	public $state;

	/**
	 * @var string
	 */
	public $zip;

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
    public function rules()
    {
        return [
            ['someAttribute', 'string'],
			['street', 'string'],
			['city', 'string'],
			['state', 'string'],
			['zip', 'string'],
			['country', 'string'],
			['countryName', 'string'],
			['countryCode', 'string'],
			['lat', 'float'],
			['lng', 'float'],
			['formattedAddress', 'string']
        ];
    }
}
