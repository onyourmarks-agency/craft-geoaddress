<?php

namespace oym\craft\geoaddress\migrations;

use Craft;
use craft\db\Migration;
use craft\db\Table;
use oym\craft\geoaddress\fields\GeoAddressField;

/**
 * m220516_144416_update_field_handle migration.
 */
class m220516_144416_update_field_handle extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->update(
            Table::FIELDS,
            [
                'type' => GeoAddressField::class,
            ],
            [
                'type' => 'OYM\GeoAddress\fields\GeoAddressField',
            ]
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->update(
            Table::FIELDS,
            [
                'type' => 'OYM\GeoAddress\fields\GeoAddressField',
            ],
            [
                'type' => GeoAddressField::class,
            ]
        );

        return false;
    }
}
