<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Constant\Constant;
use App\Model\Service\XmlService;

class UserManager{

    public function __construct(
        private XmlService $xmlService,
    )
    {
    }
}