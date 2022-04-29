<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Constant\Constant;
use App\Model\Service\XmlService;

class AttributeManager
{


    /***/
    public function __construct(
        private XmlService $xmlService,
    ) {
    }

    /**
     * @return \SimpleXMLElement|bool
     */
    public function getContent(): \SimpleXMLElement|bool
    {
        return $this->xmlService->getContent(Constant::ATTR_FILE);
    }

    /**
     * @return array
     */
    public function getArrayContent(): array
    {
        $content = [];
        $i = 0;
        $xml = $this->xmlService->getContent(Constant::ATTR_FILE);

        foreach ($xml as $parent) {
            foreach ((array)$parent as $key => $value) {
                \Tracy\Debugger::barDump($value);
                if (is_array($value)) {
                    foreach ($value as $subkey => $subvalue) {
                        $content[$i][$subkey] = $subvalue;
                    }
                } else {
                    $content[$i][$key] = $value;
                }
            }
            $i++;
        }
        return $content;
    }

    /**
     * @param $values
     */
    public function update($values)
    {
        try {
            $this->xmlService->setContent(Constant::ATTR_FILE, $values, Constant::ATTR_PARENT);
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
        }
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->xmlService->getContent(Constant::ATTR_FILE));
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        try {
            $this->xmlService->delete(Constant::ATTR_FILE, $id);
            return true;
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
            return false;
        }
    }
}