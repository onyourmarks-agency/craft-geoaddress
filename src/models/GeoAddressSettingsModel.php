<?php

declare(strict_types=1);

namespace TDE\GeoAddress\models;

use craft\base\Model;

class GeoAddressSettingsModel extends Model
{
    public ?string $googleApiKey;

    public function rules(): array
    {
        return [
            ['googleApiKey', 'string']
        ];
    }
}
