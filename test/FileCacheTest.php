<?php

require_once 'FileCache.php';

class FileCacheTest extends PHPUnit_Framework_TestCase {

    public function testNonExistentFilesAreNotInCache() {
        $fs = $this->getMockBuilder('FileSystem')->getMock();
        $fs->method('file_exists')->willReturn(false);
        $cache = new FileCache($fs);

        $this->assertFalse($cache->isInCache(null, null));
    }

}
