<?php

namespace TDE\GeoAddress\twigextensions;

use TDE\GeoAddress\GeoAddress;

/**
 * Class GeoAddressTwigExtension
 *
 * @package TDE\GeoAddress\twigextensions
 */
class GeoAddressTwigExtension extends \Twig_Extension
{
	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return 'GeoAddress';
	}

	/**
	 * @inheritdoc
	 */
	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter('geoAddressFilter', [$this, 'geoAddressFilter'])
		];
	}

    /**
     * @inheritdoc
     */
	public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('geoAddressCountries', [$this, 'geoAddressCountries'])
        ];
    }

    /**
	 * Apply the geo-address filter
	 *
	 * @param $entries
	 * @param null $lat
	 * @param null $lng
	 * @param null $radius
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function geoAddressFilter(array $entries = [], $lat = null, $lng = null, $radius = null)
	{
		$lat = $lat ?: (!empty($_GET['lat']) ? $_GET['lat'] : null);
		$lng = $lng ?: (!empty($_GET['lng']) ? $_GET['lng'] : null);
		$radius = $radius ?: (!empty($_GET['radius']) ? $_GET['radius'] : 20);

		if (!$lat || !$lng) {
			return $entries;
		}

		return GeoAddress::getInstance()->geoAddressService->filterEntries($entries, $lat, $lng, $radius);
	}

    /**
     * Return a list of available countries from the config
     *
     * @return array
     */
    public function geoAddressCountries()
    {
        $config = \Craft::$app->getConfig()->getConfigFromFile('geoaddress');
        if (!empty($config['countries'])) {
            return (array) $config['countries'];
        }

        return [];
    }
}