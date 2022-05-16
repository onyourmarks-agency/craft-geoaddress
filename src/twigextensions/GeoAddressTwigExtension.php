<?php

declare(strict_types=1);

namespace tde\craft\geoaddress\twigextensions;

use tde\craft\geoaddress\GeoAddress;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GeoAddressTwigExtension extends AbstractExtension
{
    public function getName(): string
    {
        return 'GeoAddress';
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('geoAddressFilter', [$this, 'geoAddressFilter'])
		];
	}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('geoAddressCountries', [$this, 'geoAddressCountries'])
        ];
    }

    public function geoAddressFilter(array $entries = [], mixed $lat = null, mixed $lng = null, mixed $radius = null): array
    {
        $lat = (float) ($lat ?: $this->getFloatParameter('lat'));
        $lng = (float) ($lng ?: $this->getFloatParameter('lng'));
        $radius = (float) ($radius ?: $this->getFloatParameter('radius', 20.0));

        if (!$lat || !$lng) {
            return $entries;
        }

        return GeoAddress::getInstance()->geoAddressService->filterEntries(
            $entries,
            $lat,
            $lng,
            $radius
        );
    }

    public function geoAddressCountries(): array
    {
        $config = \Craft::$app->getConfig()->getConfigFromFile('geoaddress');
        return $config['countries'] ?? [];
    }

    protected function getFloatParameter(string $name, ?float $default = null): float
    {
        $value = $_GET[$name] ?? $default;
        if ($value) {
            return (float) $value;
        }

        return $default;
    }
}
