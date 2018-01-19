<?php

namespace TDE\GeoAddress;

use Craft;
use craft\base\Plugin;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;
use craft\web\twig\variables\CraftVariable;
use TDE\GeoAddress\models\GeoAddressSettingsModel;
use TDE\GeoAddress\services\GeoAddressService;
use TDE\GeoAddress\twigextensions\GeoAddressTwigExtension;
use yii\base\Event;
use TDE\GeoAddress\fields\GeoAddressField;

/**
 * Class GeoAddress
 *
 * @property GeoAddressService $geoAddressService
 *
 * @package TDE\GeoAddress
 */
class GeoAddress extends Plugin
{
    /**
     * @var GeoAddress
     */
    public static $plugin;

	/**
	 * Initialize
	 */
    public function init() {
		parent::init();

		self::$plugin = $this;

		// Register our fields
		Event::on(
			Fields::class,
			Fields::EVENT_REGISTER_FIELD_TYPES,
			function (RegisterComponentTypesEvent $event) {
				$event->types[] = GeoAddressField::class;
			}
		);

		// Register our Twig extesion
		Craft::$app->view->registerTwigExtension(new GeoAddressTwigExtension());
	}

	/**
	 * @return GeoAddressSettingsModel
	 */
	public function createSettingsModel()
	{
		return new GeoAddressSettingsModel();
	}

	/**
	 * @return string
	 * @throws \Twig_Error_Loader
	 * @throws \yii\base\Exception
	 */
	public function settingsHtml() : string
	{
		return Craft::$app->view->renderTemplate(
			'geoaddress/settings',
			[
				'settings' => $this->getSettings()
			]
		);
	}
}
