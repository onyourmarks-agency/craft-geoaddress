<?php

namespace TDE\GeoAddress\assetbundles\geoaddressfield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * Class GeoAddressFieldAsset
 *
 * @package TDE\GeoAddress\assetbundles\geoaddressfield
 */
class GeoAddressFieldAsset extends AssetBundle
{
	/**
	 * Initializes the bundle.
	 */
	public function init()
	{
		// define the path that your publishable resources live
		$this->sourcePath = "@TDE/GeoAddress/assetbundles/geoaddressfield/dist";

		// define the dependencies
		$this->depends = [
			CpAsset::class,
		];

		// define the relative path to CSS/JS files that should be registered with the page
		// when this asset bundle is registered
		$this->css = [
			'css/GeoAddressField.css',
		];

		parent::init();
	}
}
