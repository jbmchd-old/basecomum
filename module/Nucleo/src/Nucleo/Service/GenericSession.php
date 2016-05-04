<?php

namespace Nucleo\Service;

use Zend\Session\Container;

class GenericSession extends Container {

    const NAME_SESSION_DEFAULT = 'global';

    private $container = null;
    private $container_array = null;

    public function __construct() {
        parent::__construct(self::NAME_SESSION_DEFAULT, $this->getManager());
        $this->container = $this->getManager()->getStorage();
        $this->container_array = $this->container->toArray();
    }

    public function getSession() {
        return $this->getSession();
    }

    public function clearAll() {
        $this->getManager()->destroy();
        return $this;
    }

    public function getArrayCopy($member = false) {
        return ($member && isset($this->container_array[$member])) ? $this->container_array[$member] : $this->container_array;
    }

    public function addInSession(array $cont, $member = self::NAME_SESSION_DEFAULT) {
        $estado_atual = (array) $this->container[$member];
        $this->container[$member] = array_merge($estado_atual, $cont);
    }

    public function removeOfSession($keys, $member = self::NAME_SESSION_DEFAULT) {
        $keys = (array) $keys;
        foreach ($keys as $key) {
            unset($this->container[$member][$key]);
        }
    }
}
