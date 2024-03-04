<?php

namespace tde\craft\geoaddress {
    spl_autoload_register(
        static function (string $className) {
            $old = 'tde\\craft\\geoaddress\\';
            $new = 'oym\\craft\\geoaddress\\';

            if (0 !== strpos($className, $old)) {
                return;
            }

            $newName = substr_replace($className, $new, 0, strlen($old));
            class_alias($newName, $className);
        },
        true,
        false,
    );

    if (!\class_exists(GeoAddress::class)) {
        /** @deprecated this is an alias for \oym\craft\geoaddress\GeoAddress */
        class GeoAddress
        {
        }
    }
}

namespace tde\craft\geoaddress\assetbundles\geoaddressfield {
    if (!\class_exists(GeoAddressFieldAsset::class)) {
        /** @deprecated this is an alias for \oym\craft\geoaddress\assetbundles\geoaddressfield\GeoAddressFieldAsset */
        class GeoAddressFieldAsset
        {
        }
    }
}

namespace tde\craft\geoaddress\fields {
    if (!\class_exists(GeoAddressField::class)) {
        /** @deprecated this is an alias for \oym\craft\geoaddress\fields\GeoAddressField */
        class GeoAddressField
        {
        }
    }
}

namespace tde\craft\geoaddress\gql\types {
    if (!\class_exists(AddressType::class)) {
        /** @deprecated this is an alias for \oym\craft\geoaddress\gql\types\AddressType */
        class AddressType
        {
        }
    }
}

namespace tde\craft\geoaddress\migrations {
    if (!\class_exists(m220516_144416_update_field_handle::class)) {
        /** @deprecated this is an alias for \oym\craft\geoaddress\migrations\m220516_144416_update_field_handle */
        class m220516_144416_update_field_handle
        {
        }
    }
}

namespace tde\craft\geoaddress\models {
    if (!\class_exists(GeoAddressModel::class)) {
        /** @deprecated this is an alias for \oym\craft\geoaddress\models\GeoAddressModel */
        class GeoAddressModel
        {
        }
    }

    if (!\class_exists(GeoAddressSettingsModel::class)) {
        /** @deprecated this is an alias for \oym\craft\geoaddress\models\GeoAddressSettingsModel */
        class GeoAddressSettingsModel
        {
        }
    }
}

namespace tde\craft\geoaddress\services {
    if (!\class_exists(GeoAddressService::class)) {
        /** @deprecated this is an alias for \oym\craft\geoaddress\services\GeoAddressService */
        class GeoAddressService
        {
        }
    }
}

namespace tde\craft\geoaddress\twigextensions {
    if (!\class_exists(GeoAddressTwigExtension::class)) {
        /** @deprecated this is an alias for \oym\craft\geoaddress\twigextensions\GeoAddressTwigExtension */
        class GeoAddressTwigExtension
        {
        }
    }
}
