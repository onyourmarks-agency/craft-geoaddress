<?php

declare(strict_types=1);

namespace oym\craft\geoaddress;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterGqlTypesEvent;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Gql;
use oym\craft\geoaddress\fields\GeoAddressField;
use oym\craft\geoaddress\gql\types\AddressType;
use oym\craft\geoaddress\models\GeoAddressSettingsModel;
use oym\craft\geoaddress\services\GeoAddressService;
use oym\craft\geoaddress\twigextensions\GeoAddressTwigExtension;
use yii\base\Event;

/**
 * @property GeoAddressService $geoAddressService
 */
class GeoAddress extends Plugin
{
    public static GeoAddress $plugin;

    public function init(): void
    {
        parent::init();

        self::$plugin = $this;

        $this->registerField();

        Craft::$app->view->registerTwigExtension(new GeoAddressTwigExtension());
    }

    public function createSettingsModel(): ?Model
    {
        return new GeoAddressSettingsModel();
    }

    public function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'geoaddress/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }

    protected function registerField(): void
    {
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            static function (RegisterComponentTypesEvent $event) {
                $event->types[] = GeoAddressField::class;
            }
        );

        Event::on(
            Gql::class,
            Gql::EVENT_REGISTER_GQL_TYPES,
            function (RegisterGqlTypesEvent $event) {
                $event->types[] = AddressType::class;
            }
        );
    }
}
