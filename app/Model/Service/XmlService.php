<?php

declare(strict_types=1);

namespace App\Model\Service;

use App\Constant\Constant;
use Nette\Utils\Random;
use Nette\Utils\Strings;
use Nette\Utils\Validators;

class XmlService
{


    /***/
    public function __construct()
    {
    }

    private function processXml($xml, $key, $value, $webalize){
        if ($key == 'id') {
            if (!$value) {
                $value = Random::generate(12);
            }
            $xml->addAttribute('id', $value);
        } else {
            $xml->addChild("$key", htmlspecialchars((string)$value ?? ''));
            if ($key != 'param') {
                if ($webalize) {
                    $xml->addChild(
                        "webalized",
                        Strings::replace(Strings::webalize($value), "/[^a-zA-Z0-9]+/", '')
                    );
                }
            }
        }
    }

    /**
     * @param $params
     * @param $xml
     */
    private function putContent($params, &$xml, $putArray, $webalize)
    {
        if($putArray){
            $parent = $xml->addChild('item');
            foreach ($params as $key => $value) {
                \Tracy\Debugger::barDump($key);
                $this->processXml($parent, $key, $value, $webalize);
            }
        } else {
            foreach ($params as $key => $value) {
                if (Validators::is($value, 'object')) {
                    if (is_numeric($key)) {
                        $key = 'item';
                    }
                    $subnode = $xml->addChild($key);
                    $this->putContent($value, $subnode, $putArray, $webalize);
                } else {
                    $this->processXml($xml, $key, $value, $webalize);
                }
            }
        }
    }

    public function putArrayContent($params, &$xml, $webalize)
    {
        $xml->addChild('item');
        foreach ($params as $key => $value) {
            if ($key == 'id') {
                if (!$value) {
                    $value = Random::generate(12);
                }
                $xml->addAttribute('id', $value);
            } else {
                $xml->addChild("$key", htmlspecialchars((string)$value ?? ''));
                if ($webalize) {
                    $xml->addChild(
                        "webalized",
                        Strings::replace(Strings::webalize($value), "/[^a-zA-Z0-9]+/", '')
                    );
                }
            }
        }
    }

    /**
     * @param $file
     * @param $params
     * @param $parent
     * @throws \Exception
     */
    public function setContent($file, $params, $parent, $putArray, $webalize)
    {
//        \Tracy\Debugger::barDump($file);
//        \Tracy\Debugger::barDump($params);
//        \Tracy\Debugger::barDump($parent);
//        \Tracy\Debugger::barDump($putArray);
//        \Tracy\Debugger::barDump($webalize);
//        die();
        $xml = new \SimpleXMLElement($parent);

        try {
                $this->putContent($params, $xml, $putArray, $webalize);
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
        }

        $xml->asXML(Constant::DB_DIR . $file);
    }

    /**
     * @param $file
     * @return \SimpleXMLElement|bool
     */
    public function getContent($file): \SimpleXMLElement|bool
    {
        return simplexml_load_file(Constant::DB_DIR . $file);
    }


    /**
     * @param $file
     * @param $id
     * @throws \Exception
     */
    public function delete($file, $id)
    {
        $dom = new \DOMDocument();
        $dom->load(Constant::DB_DIR . $file);

        $xpath = new \DomXPath($dom);
        $toDelete = $xpath->query('//*[@id="' . $id . '"]');
        foreach ($toDelete as $item) {
            $item->remove();
        }
        $xml = new \SimpleXMLElement($dom->saveXml());
        $xml->asXML(Constant::DB_DIR . $file);
    }

    public function get($file, $id){

        $dom = new \DOMDocument();
        $dom->load(Constant::DB_DIR . $file);

        $xpath = new \DomXPath($dom);
        $toDelete = $xpath->query('//*[@id="' . $id . '"]');
        \Tracy\Debugger::barDump($toDelete->item(0));
//        foreach ($toDelete as $item) {
//            \Tracy\Debugger::barDump($item);
//        }
    }


    /**
     * @param $file
     * @param $params
     * @param $parent
     * @param false $putArray
     * @throws \Exception
     */
    public function addContent($file, $params, $parent, $putArray, $webalize)
    {
        if (file_exists(Constant::DB_DIR . $file)) {
            $xml = simplexml_load_file(Constant::DB_DIR . $file);
            try {
                if ($putArray) {
                    $this->putArrayContent($params, $xml, $webalize);
                } else {
                    $this->putContent($params, $xml, $webalize);
                }
            } catch (\Exception $e) {
                \Tracy\Debugger::log($e->getMessage());
            }
            $xml->asXML($file);
        } else {
            $this->setContent($file, $params, $parent, $putArray, $webalize);
        }
    }
}