<?php

namespace TDE\GeoAddress\services;

use Craft;
use craft\base\Component;
use TDE\GeoAddress\GeoAddress;
use TDE\GeoAddress\models\GeoAddressModel;

/**
 * Class GeoAddressService
 *
 * @package TDE\GeoAddress\services
 */
class GeoAddressService extends Component
{
	/**
	 * @param string $address
	 * @param string $country
	 * @return array
	 */
    public function getCoordsByAddress(string $address, string $country)
    {
        $requestUrl = 'https://maps.googleapis.com/maps/api/geocode/json';
        $requestUrl .= '?address=' . rawurlencode($address);
        $requestUrl .= '&key=' . GeoAddress::getInstance()->getSettings()->googleApiKey;

        $address = [
            'lat' => null,
            'lng' => null,
            'formattedAddress' => null,
            'countryName' => null,
            'countryCode' => null,
        ];

        if (!GeoAddress::getInstance()?->getSettings()->googleApiKey) {
            return $address;
        }

        $ch =  curl_init($requestUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $result = json_decode(curl_exec($ch), false, 512, JSON_THROW_ON_ERROR);

		// no results
		if ($result->status !== 'OK' || empty($result->results)) {
			Craft::warning(
				Craft::t(
					'geoaddress',
					'GeoAddress coding failed: ' . $result->status
				),
				__METHOD__
			);

			return $address;
		}

        $addressComponent = null;
        foreach ($result->results as $addressResult) {
            foreach ($addressResult->address_components as $component) {
                if (!in_array('country', $component->types)) {
                    continue;
                }

                if ($component->long_name !== $country) {
                    continue;
                }

                $addressComponent = $addressResult;
                break 2;
            }
        }

        // get the country name & code
        if (isset($addressComponent->address_components)) {
            foreach ($addressComponent->address_components as $component) {
                if (count($component->types) === 0 || $component->types[0] !== 'country') {
                    continue;
                }

                $address['countryName'] = $component->long_name;
                $address['countryCode'] = $component->short_name;
            }
        }

        // get the geometry
        if (isset($addressComponent->geometry)) {
            $address['lat'] = $addressComponent->geometry->location->lat;
            $address['lng'] = $addressComponent->geometry->location->lng;
        }

        if (isset($addressComponent->formatted_address)) {
            $address['formattedAddress'] = $addressComponent->formatted_address;
        }

        return $address;
    }

	/**
	 * Filter the given entries with the latitude & longitude
	 *
	 * @param array $entries
	 * @param $lat
	 * @param $lng
	 * @param $radius
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function filterEntries(array $entries, $lat, $lng, $radius)
	{
		$filterResults = [];

		/** @var \craft\elements\Entry $entry */
		foreach ($entries as $entry) {

			if (!array_key_exists('address', $entry->fields())) {
				throw new \Exception('The given entry for geo-address filtering does not contain a GeoAddress-field with the handle \'address\'.');
			}

			$filterDistance = $this->calculateDistance($lat, $lng, $entry['address']['lat'], $entry['address']['lng']);
			if ($filterDistance > $radius) {
				continue;
			}

			// add the distance, might be useful for the user
			/** @var GeoAddressModel $model */
			$model = $entry->getFieldValue('address');
			$model->filterDistance = $filterDistance;
			$entry->setFieldValue('address', $model);

			$filterResults[] = $entry;
		}

		// sort with the closest first
		usort($filterResults, function($a, $b) {
			return $a->address['filterDistance'] - $b->address['filterDistance'];
		});

		return $filterResults;
	}

	/**
	 * Calculate metric distance
	 *
	 * @param $lat1
	 * @param $lng1
	 * @param $lat2
	 * @param $lng2
	 *
	 * @return float
	 */
	protected function calculateDistance($lat1, $lng1, $lat2, $lng2)
	{
		// convert degrees to radians
		$lat1 = deg2rad((float) $lat1);
		$lng1 = deg2rad((float) $lng1);
		$lat2 = deg2rad((float) $lat2);
		$lng2 = deg2rad((float) $lng2);

		// great circle distance formula
		return 6371.009 * acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lng1 - $lng2));
	}
}
