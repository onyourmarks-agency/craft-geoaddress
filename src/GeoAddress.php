<?php

declare(strict_types=1);

namespace tde\craft\geoaddress;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;
use tde\craft\geoaddress\models\GeoAddressSettingsModel;
use tde\craft\geoaddress\services\GeoAddressService;
use tde\craft\geoaddress\twigextensions\GeoAddressTwigExtension;
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
