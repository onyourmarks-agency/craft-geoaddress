<?php

declare(strict_types=1);

namespace TDE\GeoAddress\services;

use Craft;
use craft\base\Component;
use TDE\GeoAddress\GeoAddress;
use TDE\GeoAddress\models\GeoAddressModel;

class GeoAddressService extends Component
{
    protected const GEOSERVICE_API = 'https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=%s';

    public function getCoordsByAddress(string $addressQuery, string $country): array
    {
        $requestUrl = sprintf(
            self::GEOSERVICE_API,
            rawurlencode($addressQuery),
            GeoAddress::getInstance()?->getSettings()->googleApiKey
        );

        $result = json_decode(file_get_contents($requestUrl), true, 512, JSON_THROW_ON_ERROR);

        $address = [
            'lat' => null,
            'lng' => null,
            'formattedAddress' => null,
            'countryName' => null,
            'countryCode' => null,
        ];

        // no results
        if (!$result || $result['status'] !== 'OK' || empty($result['results'])) {
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
        foreach ($result['results'] as $addressResult) {
            foreach ($addressResult['address_components'] as $component) {
                if (!in_array('country', $component['types'], true)) {
                    continue;
                }

                if ($component['long_name'] !== $country) {
                    continue;
                }

                $addressComponent = $addressResult;
                break 2;
            }
        }

        // get the country name & code
        if (isset($addressComponent['address_components'])) {
            foreach ($addressComponent['address_components'] as $component) {
                if (count($component['types']) === 0 || $component['types'][0] !== 'country') {
                    continue;
                }

                $address['countryName'] = $component['long_name'];
                $address['countryCode'] = $component['short_name'];
            }
        }


        // get the geometry
        if (isset($addressComponent['geometry'])) {
            $address['lat'] = $addressComponent['geometry']['location']['lat'];
            $address['lng'] = $addressComponent['geometry']['location']['lng'];
        }

        if (isset($addressComponent['formatted_address'])) {
            $address['formattedAddress'] = $addressComponent['formatted_address'];
        }

        return $address;
    }

    public function filterEntries(array $entries, float $lat, float $lng, float $radius): array
    {
        $filterResults = [];

        /** @var \craft\elements\Entry $entry */
        foreach ($entries as $entry) {
            if (!array_key_exists('address', $entry->fields())) {
                throw new \RuntimeException(
                    'The given entry for geo-address filtering does not contain a GeoAddress-field with the handle \'address\'.'
                );
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
        usort($filterResults, static function ($a, $b) {
            return $a->address['filterDistance'] - $b->address['filterDistance'];
        });

        return $filterResults;
    }

    protected function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        // convert degrees to radians
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        // great circle distance formula
        return 6371.009 * acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lng1 - $lng2));
    }
}
