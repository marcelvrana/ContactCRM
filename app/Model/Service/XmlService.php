<?php

declare(strict_types=1);

namespace App\Model\Service;

use App\Constant\Constant;
use Nette\Utils\Random;
use Nette\Utils\Strings;

class XmlService{


    /***/
    public function __construct()
    {
    }


       public function putContent($params, &$xml)
        {
            foreach ($params as $key => $value) {
                \Tracy\Debugger::barDump($key);
                \Tracy\Debugger::barDump($value);
                \Tracy\Debugger::barDump(is_array($value));
                if (is_array($value)) {
                    if (is_numeric($key)) {
                        $key = 'item';
                    }
                    $subnode = $xml->addChild($key);
                    $this->putContent($value, $subnode);
                } else {
                    $xml->addChild("$key", htmlspecialchars("$value"));
                    $xml->addChild("webalized", htmlspecialchars(Strings::webalize($value)));
                }
            }
//            foreach ($params as $param){
//                $item = $xml->addChild('item');
//                foreach ($param as $key => $value){
//                    if($key == 'id'){
//                        if (!$value){
//                            $value = Random::generate(12);
//                        }
//                        $item->addAttribute('itemId', $value);
//                    } else {
//                        $item->addChild("$key", htmlspecialchars("$value"));
//                        $item->addChild("webalized", htmlspecialchars(Strings::webalize($value)));
//                    }
//
//                }
//            }
//            return $item;
        }



    public function create($file, $params, $parent){

        $xml = new \SimpleXMLElement($parent);

        try {
            $this->putContent($xml, $params);
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
        }



        $xml->asXML(Constant::DB_DIR . $file);
    }

    public function get($file){

    }

    public function update($file, $params, $parent){
        if( is_file(Constant::DB_DIR . $file )){
            $dom = new \DOMDocument();
            $dom->loadXML(Constant::DB_DIR . $file);
            $path = new \DOMXPath($dom);

        } else {
            $this->create($file, $params, $parent);
        }
    }

    public function delete($file, $item){

    }
}