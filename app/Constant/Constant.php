<?php

declare(strict_types=1);

namespace App\Constant;

class Constant
{
    public const ATTR_FILE = 'attributes.xml';
    public const ATTR_PARENT = '<?xml version="1.0"?><attributes></attributes>';

    public const USER_FILE = 'users.xml';
    public const USER_PARENT = '<?xml version="1.0"?><users></users>';

    const DB_DIR = APP_DIR . '/db/';
    const ATTR_PARAMS = [
        '' => 'Normal',
        'date' => 'Date',
        'sex' => 'Sex',
    ];

    const SEX_ITEMS = [
        'male' => 'Male',
        'woman' => 'woman',
        'other' => 'Other',
    ];

}