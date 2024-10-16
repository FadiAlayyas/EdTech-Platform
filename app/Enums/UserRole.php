<?php

namespace App\Enums;

use App\Traits\EnumHelper;

enum UserRole: int
{
    use EnumHelper;

    case Admin = 1;
    case Student = 2;
    case Teacher = 3;
}
