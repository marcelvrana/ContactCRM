<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Constant\Constant;
use App\Model\Service\XmlService;

class UserManager
{

    public function __construct(
        private XmlService $xmlService,
    ) {
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

    /**
     * @param $id
     * @return bool|array
     */
    public function get($id): bool|array
    {
        try {
            return $this->makeArrayFromItem($this->xmlService->get(Constant::USER_FILE, $id));
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
            return false;
        }
    }

    /**
     * @param $date
     * @return int|string
     */
    private function calcAge($date): int|string
    {
        $birthDate = explode("/", $date);
        return (date(
            "md",
            (int)date("U", mktime(0, 0, 0, (int)$birthDate[0], (int)$birthDate[1], (int)$birthDate[2]))
        ) > date("md")
            ? ((date("Y") - $birthDate[2]) - 1)
            : (date("Y") - $birthDate[2]));
    }

    /**
     * @return array
     */
    public function getContentForChart(): array
    {
        $content = $this->getContent();
        $data = [];
        $i = 0;
        foreach ($content as $cont) {
            $user = (array)$cont;
            $data['label'][$i] = $user['name'] . ' ' . $user['surname'];
            $data['years'][$i] = $this->calcAge($user['dateofbirth']);
            $i++;
        }
        return $data;
    }

    /**
     * @param $params
     */
    public function add($params)
    {
        try {
            $this->xmlService->setContent(Constant::USER_FILE, $params, Constant::USER_PARENT, true, false);
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
        }
    }

    /**
     * @param $item
     * @return array
     */
    private function makeArrayFromItem($item): array
    {
        $content = [];

        foreach ($item as $parent) {
            foreach ((array)$parent as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subkey => $subvalue) {
                        $content[$subkey] = $subvalue;
                    }
                } else {
                    $content[$key] = $value;
                }
            }
        }
        return $content;
    }

    /**
     * @param $params
     * @param $id
     */
    public function update($params, $id)
    {
        try {
            $this->delete($id);
            $this->add($params);
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        try {
            $this->xmlService->delete(Constant::USER_FILE, $id);
            return true;
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
            return false;
        }
    }
}