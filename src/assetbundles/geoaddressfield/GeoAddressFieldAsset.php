<?php

namespace tde\craft\geoaddress\assetbundles\geoaddressfield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class GeoAddressFieldAsset extends AssetBundle
{
    public function init(): void
    {
        $this->sourcePath = "@TDE/GeoAddress/assetbundles/geoaddressfield/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->css = [
            'css/GeoAddressField.css',
        ];

        parent::init();
    }
}
