<?php

namespace TDE\GeoAddress\models;

use Craft;
use craft\base\Model;

/**
 * Class GeoAddressSettingsModel
 *
 * @package TDE\GeoAddress\models
 */
class GeoAddressSettingsModel extends Model
{
	/**
	 * @var string
	 */
	public $googleApiKey;

	/**
	 * @return array
	 */
	public function rules()
	{
		return [
			['googleApiKey', 'string']
		];
	}
}