<?php

namespace TDE\GeoAddress\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use TDE\GeoAddress\GeoAddress;
use TDE\GeoAddress\models\GeoAddressModel;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * Class GeoAddressField
 *
 * @package TDE\GeoAddress\fields
 */
class GeoAddressField extends Field
{
    /**
     * Returns the display name of this class.
     *
     * @return string The display name of this class.
     */
    public static function displayName(): string
    {
        return 'Geo Address';
    }

	/**
	 * @param $value
	 * @param \craft\base\ElementInterface|NULL $element
	 *
	 * @return string
	 * @throws \Twig_Error_Loader
	 * @throws \yii\base\Exception
	 */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
    	if (!$value) {
			$value = $this->normalizeValue($value);
		}

    	// Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
        	'geoaddress/_components/fields/GeoAddressField_input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'id' => $id,
                'namespacedId' => $namespacedId,
            ]
        );
    }

	/**
	 * @param $value
	 * @param \craft\base\ElementInterface|NULL $element
	 *
	 * @return array|mixed
	 */
	public function normalizeValue($value, ElementInterface $element = NULL)
	{
		if (empty($value)) {
			$model = new GeoAddressModel();
			$value = $model->getAttributes();
		}

		if (!is_array($value)) {
			$value = json_decode($value);
		}

		return $value;
	}

	/**
	 * @param array $value
	 * @param \craft\base\ElementInterface|NULL $element
	 *
	 * @return array
	 */
	public function serializeValue($value, ElementInterface $element = NULL)
	{
		return array_merge(
			$value,
			GeoAddress::getInstance()->geoAddressService->getCoordsByAddress($value)
		);
	}
}