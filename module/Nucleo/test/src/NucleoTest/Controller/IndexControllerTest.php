<?php

namespace NucleoTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class IndexControllerTest extends AbstractHttpControllerTestCase {
    
    protected $traceError = true;
    
    public function setUp() {
        $this->setApplicationConfig(
            include '/var/www/pensadores/module/Admin/TestConfig.config.php'
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed() {
        $this->dispatch('/');
        $this->assertResponseStatusCode(200);
    }
}