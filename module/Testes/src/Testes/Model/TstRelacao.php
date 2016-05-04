<?php

namespace Testes\Model;

use Nucleo\Service\Model;

class TstRelacao extends Model {
	
    public function __construct($options = null) {
        parent::__construct('tst_id', $options);
    }
}
?>