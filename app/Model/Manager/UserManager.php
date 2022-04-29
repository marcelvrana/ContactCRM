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

    /**
     * @return bool
     */
    public function exist(): bool
    {
        return file_exists(Constant::DB_DIR . Constant::USER_FILE);
    }

    /**
     * @return \SimpleXMLElement|bool
     */
    public function getContent(): \SimpleXMLElement|bool
    {
        return $this->xmlService->getContent(Constant::USER_FILE);
    }


    
    public function add($params){
        try {
            $this->xmlService->setContent(Constant::USER_FILE, $params, Constant::USER_PARENT, true, false);
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return false|void
     */
    public function get($id)
    {
        try {
            return $this->xmlService->get(Constant::USER_FILE, $id);

        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
            return false;
        }
    }

    public function delete($id){
        try {
            $this->xmlService->delete(Constant::USER_FILE, $id);
            return true;
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
            return false;
        }
    }
}