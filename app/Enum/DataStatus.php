<?php

namespace App\Enum;

enum DataStatus: int {
    case Active = 1;
    case Inactive = 0;
    case Deleted = 3;
}
