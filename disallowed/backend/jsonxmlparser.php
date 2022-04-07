<?php

class JSONXMLParser {
    public static function json_to_xml($array, $xml= false) {
        if($xml === false){
            echo "false";
            $xml = new SimpleXMLElement('<test>');
        }
     
        foreach($array as $key => $value){
            if(is_array($value)){
                JSONXMLParser::json_to_xml($value, $xml->addChild($key));
                echo "recursive";
            } else {
                $xml->addChild($key, $value);
                echo "added: ";
                print_r($value);
                echo "</br>";
            }
        }
     
        return $xml->asXML();
    }
}