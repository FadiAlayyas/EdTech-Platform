<?php

namespace App\Enums;

use App\Traits\EnumHelper;

enum AssignmentStatus: int
{
    use EnumHelper;

    case Active = 1;
    case Inactive = 2;
    case Closed = 3;
}
