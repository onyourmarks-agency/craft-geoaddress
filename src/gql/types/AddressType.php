<?php

namespace oym\craft\geoaddress\gql\types;

use craft\gql\GqlEntityRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AddressType
{
    public static function getName(): string
    {
        return 'address_Address';
    }

    public static function getType(): Type
    {
        return GqlEntityRegistry::getOrCreate(
            self::class,
            static fn() => new ObjectType(
                [
                    'name' => static::getName(),
                    'fields' => self::class . '::getFieldDefinitions',
                    'description' => 'This is the interface implemented by all address fields.',
                ]
            )
        );
    }

    public static function getFieldDefinitions(): array
    {
        return [
            'name' => [
                'name' => 'name',
                'type' => Type::string(),
            ],
            'street' => [
                'name' => 'street',
                'type' => Type::string(),
            ],
            'city' => [
                'name' => 'city',
                'type' => Type::string(),
            ],
            'zip' => [
                'name' => 'zip',
                'type' => Type::string(),
            ],
            'state' => [
                'name' => 'state',
                'type' => Type::string(),
            ],
            'country' => [
                'name' => 'country',
                'type' => Type::string(),
            ],
            'countryName' => [
                'name' => 'countryName',
                'type' => Type::string(),
            ],
            'countryCode' => [
                'name' => 'countryCode',
                'type' => Type::string(),
            ],
            'lat' => [
                'name' => 'lat',
                'type' => Type::float(),
            ],
            'lng' => [
                'name' => 'lng',
                'type' => Type::float(),
            ],
            'formattedAddress' => [
                'name' => 'formattedAddress',
                'type' => Type::string(),
            ],
        ];
    }
}
