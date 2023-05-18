<?php

declare(strict_types=1);

namespace App\Util\Login\Workers;

use App\Util\Database\SQLQuery;

class SettingsWorker
{
    public const REGEX = [
        'username' => '/^(?=.*[A-Z])[A-Z\d!#$%?&*]{6,}$/i',
        'password' => '/^(?=.*[A-Z])(?=.*\d)[A-Z\d!@#$%?&*]{8,}$/i',
        'email' => "/^(?!\.)((\.)?[A-Z\d!#$%&'*+\-\/=?^_`{|}~]+)+@(?!\.)((\.)?[A-Z\d_]+)+(\.[A-Z\d]{2,3})$/i",
        'displayName' => "/^(?!\s)[^<>]{6,40}$/i"
    ];

    public function __construct()
    {
        
    }

    public function changeAccountSettings(array $accountInfo, array $settings){        
        foreach($settings as $propertyName => $value){
            if(!key_exists($propertyName, $accountInfo)){
                unset($settings[$propertyName]);
                continue;
            }
            if($accountInfo[$propertyName] === $value){
                unset($settings[$propertyName]);
            }
        }
        (new SQLQuery())->updateTableRow('usersInfo', ['id' => $accountInfo['id']], $settings);
    }

    public function validateAccountSettings(array $settings){
        $valid = true;
        foreach($settings as $propertyName => $value){
            if(key_exists($propertyName, self::REGEX)){
                $valid = preg_match(self::REGEX[$propertyName], $value);
            }

            if(!$valid){
                break;
            }
        }
        
        return $valid;
    }


}