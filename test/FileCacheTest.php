<?php

require_once 'FileCache.php';

class FileCacheTest extends PHPUnit_Framework_TestCase {

    public function testIsInstanciable() {
      $this->assertNotNull(new FileCache());
    }
    
}
