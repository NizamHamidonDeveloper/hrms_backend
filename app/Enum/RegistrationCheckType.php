<?php
 
namespace App\Enum;

enum RegistrationCheckType: string {
    case Username = 'username';
    case StaffNo = 'staffno';
    case Email = 'email';
}
