<?php

namespace TDE\GeoAddress\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use craft\helpers\Db;
use craft\helpers\Html;
use TDE\GeoAddress\GeoAddress;
use TDE\GeoAddress\models\GeoAddressModel;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * Class GeoAddressField
 *
 * @package TDE\GeoAddress\fields
 */
class GeoAddressField extends Field implements PreviewableFieldInterface
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
			$value = json_decode($value, true);
		}

		$value['zip'] = str_replace(' ', '', $value['zip'] ?? '');

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

    /**
     * @return string
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @param string $value
     * @param ElementInterface $element
     * @return string
     */
    public function getTableAttributeHtml($value, ElementInterface $element): string
    {
        $label = '';

        if (!empty($value['street'])) {
            $label .= $value['street'];
        }

        if (!empty($value['city'])) {
            $label .= (!empty($label) ? ', ' : '') . $value['city'];
        }

        if (!empty($value['countryName'])) {
            $label .= (!empty($label) ? ', ' : '') . $value['countryName'];
        }

        if (empty($label)) {
            return '';
        }

        $html = Html::tag('span', $label);

        if (!empty($value['lat']) && !empty($value['lng'])) {
            $html = Html::tag('a', $html, [
                'href' => sprintf('https://maps.google.com/?q=%s,%s', $value['lat'], $value['lng']),
                'target' => '_blank',
            ]);
        }

        return $html;
    }
}
