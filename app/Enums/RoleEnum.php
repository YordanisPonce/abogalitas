<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';


    public static function getValues()
    {
        return array_map(
            fn($item) => $item->value,
            static::cases()
        );
    }

}
