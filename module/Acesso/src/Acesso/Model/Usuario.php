<?php

namespace Acesso\Model;

use Nucleo\Service\Model;

class Usuario extends Model {
    public function __construct($options = null) {
        parent::__construct('usr_id', $options);
    }
}