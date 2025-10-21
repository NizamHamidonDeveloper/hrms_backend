<?php

namespace App\Enum;

enum LogAttributes: string {
    case LogAction = 'log_action';
    case LogEnabled = 'log_enabled';
    case LogType = 'log_type';
    case LogUserId = 'log_user_id';
}
