<?php

namespace App\Enums;

use App\Traits\EnumHelper;

enum CourseStatus: int
{
    use EnumHelper;

    case Draft = 1;
    case Published = 2;
    case Archived = 3;
}
