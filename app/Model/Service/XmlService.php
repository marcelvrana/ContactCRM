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


    private function putContent($params, &$xml)
    {
        foreach ($params as $key => $value) {
            if (Validators::is($value, 'object')) {
                if (is_numeric($key)) {
                    $key = 'item';
                }
                $subnode = $xml->addChild($key);
                $this->putContent($value, $subnode);
            } else {
                if ($key == 'id') {
                    if (!$value) {
                        $value = Random::generate(12);
                    }
                    $xml->addAttribute('id', $value);
                } else {
                    $xml->addChild("$key", htmlspecialchars((string) $value ?? ''));
                    if($key != 'param'){
                        $xml->addChild("webalized", htmlspecialchars(Strings::webalize($value)));
                    }
                }
            }
        }
    }


    public function setContent($file, $params, $parent)
    {
        $xml = new \SimpleXMLElement($parent);

        try {
            $this->putContent($params, $xml);
        } catch (\Exception $e) {
            \Tracy\Debugger::log($e->getMessage());
        }

        $xml->asXML(Constant::DB_DIR . $file);
    }

    public function getContent($file)
    {
        return simplexml_load_file( Constant::DB_DIR . $file);
    }

//    public function update($file, $params, $parent){
//        if( is_file(Constant::DB_DIR . $file )){
//            $dom = new \DOMDocument();
//            $dom->loadXML(Constant::DB_DIR . $file);
//            $path = new \DOMXPath($dom);
//
//        } else {
//            $this->create($file, $params, $parent);
//        }
//    }

    public function delete($file, $id)
    {
        $library = new \SimpleXMLElement(Constant::DB_DIR . $file);
        \Tracy\Debugger::barDump($library);
        die();
        $book = $library->xpath('/library/book[author="J.R.R.Tolkein"]');
        $book[0]->title .= ' Series';
        // header("Content-type: text/xml");
        // echo $library->asXML();
        $library->asXML('library_modified.xml');
    }
}