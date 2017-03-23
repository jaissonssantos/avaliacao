<?php

class Iugu_Marketplace extends APIResource
{
    public static function create($attributes = [])
    {
        if (!isset($attributes['name'])) {
            throw new IuguException('Missing name costumer');
        }

        return self::API()->request(
            'POST',
            Iugu::getBaseURI().'/marketplace/create_account',
            $attributes
        );
    }

}
