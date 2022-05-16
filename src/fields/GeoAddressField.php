<?php

namespace TDE\GeoAddress\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use craft\helpers\Html;
use TDE\GeoAddress\GeoAddress;
use TDE\GeoAddress\models\GeoAddressModel;
use yii\db\Schema;
use craft\helpers\Json;

class GeoAddressField extends Field implements PreviewableFieldInterface
{
    public static function displayName(): string
    {
        return 'Geo Address';
    }

    public function getInputHtml($value, ElementInterface $element = null): string
    {
    	if (!$value) {
			$value = $this->normalizeValue($value);
		}

        $id = Html::id($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

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

    public function normalizeValue(mixed $value, ?ElementInterface $element = null): GeoAddressModel
    {
        if (is_string($value)) {
            $value = Json::decodeIfJson($value);
        }

        if ($value instanceof GeoAddressModel) {
            $model = $value;
        } elseif (is_array($value)) {
            $model = new GeoAddressModel($value);
        } else {
            $model = new GeoAddressModel();
        }

        return $model;
    }

    public function serializeValue(mixed $value, ?ElementInterface $element = null): GeoAddressModel
    {
        /** @var GeoAddressModel $model */
        $model = $value;

        // normalize zip
        $model->zip = trim(str_replace(' ', '', $model->zip));

        $isChanged = !$element || ($element && $model->getAddress() !== $element->getFieldValue($this->handle)->getAddress());
        if ($isChanged || (!($model->lat && $model->lng) && $model->getAddress())) {
            $result = GeoAddress::getInstance()->geoAddressService->getCoordsByAddress($model->getAddress(), $model->country);

            $model->setAttributes(array_filter($result));
        }

        $element?->setFieldValue($this->handle, $model);

        return $model;
    }

    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    public function getTableAttributeHtml(mixed $value, ElementInterface $element): string
    {
        /** @var GeoAddressModel $model */
        $model = $value;

        $label = '';

        if ($model->street) {
            $label .= $model->street;
        }

        if ($model->city) {
            $label .= ($label ? ', ' : '') . $model->city;
        }

        if ($model->countryName) {
            $label .= ($label ? ', ' : '') . $model->countryName;
        }

        if (!$label) {
            return '';
        }

        $html = Html::tag('span', $label);

        if ($model->lat && $model->lng) {
            $html = Html::tag('a', $html, [
                'href' => sprintf('https://maps.google.com/?q=%s,%s', $model->lat, $model->lng),
                'target' => '_blank',
            ]);
        }

        return $html;
    }
}
