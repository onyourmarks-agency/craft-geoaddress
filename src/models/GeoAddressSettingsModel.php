<?php

declare(strict_types=1);

namespace tde\craft\geoaddress\models;

use craft\base\Model;

class GeoAddressSettingsModel extends Model
{
    public ?string $googleApiKey = null;

    public function rules(): array
    {
        return [
            ['googleApiKey', 'string']
        ];
    }
}
