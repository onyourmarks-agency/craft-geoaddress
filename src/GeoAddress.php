<?php

declare(strict_types=1);

namespace TDE\GeoAddress;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;
use TDE\GeoAddress\models\GeoAddressSettingsModel;
use TDE\GeoAddress\services\GeoAddressService;
use TDE\GeoAddress\twigextensions\GeoAddressTwigExtension;
use yii\base\Event;
use TDE\GeoAddress\fields\GeoAddressField;

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
    }
}
