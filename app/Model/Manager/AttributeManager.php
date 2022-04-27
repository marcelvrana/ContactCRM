<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Constant\Constant;
use App\Model\Service\XmlService;

class AttributeManager {


    /***/
    public function __construct(
        private XmlService $xmlService,
    )
    {
    }

    public function getContent(){
        return $this->xmlService->getContent(Constant::ATTR_FILE);
    }

    public function update($values){
        try {
            $this->xmlService->create(Constant::ATTR_FILE, $values, Constant::ATTR_PARENT);
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
        }
    }
}