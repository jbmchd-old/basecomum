<?php

namespace Nucleo\Service;

use ZeDb\Entity as ZeDbEntity;

class Entity extends ZeDbEntity {
    
    private $data = [];
    
    public function exchangeArray($data){
        $this->data = $data;
        (new \Zend\Stdlib\Hydrator\ClassMethods())->hydrate($data, $this);
    }
    
    public function tableFill(Array $data){
        (new \Zend\Stdlib\Hydrator\ClassMethods())->hydrate($data, $this);
        return $this;
    }
    
    public function toArray() {
        $array = [];
        $array = (new \Zend\Stdlib\Hydrator\ClassMethods())->extract($this);
        if(sizeof($array) >= sizeof($this->data)){ return $array; }
        
        $get_methods = $this->attrToGetMethods();
        if(sizeof($get_methods)){
            foreach ($get_methods as $attr => $method) {
                $valor = $this->$method();
                if($valor !== '[Method not found]'){
                    $array[$attr] = $valor;
                }
            }
        }
        return $array;
    }
    
    public function __call($method, $arguments) {
        return '[Method not found]';
    }

    private function attrToGetMethods($data=null){
        if(sizeof($this->data)){ $data = $this->data; }
        $gettersName = [];
        foreach ($data as $property => $value) {
            $gettersName[$property] = 'get' . implode('', explode(' ', ucwords(str_replace('_', ' ',$property))));
        }
        return $gettersName;
    }

}
