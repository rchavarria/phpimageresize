<?php

require_once 'FileCache.php';

class FileCacheTest extends PHPUnit_Framework_TestCase {

    public function testNonExistentFilesAreNotInCache() {
        $fs = $this->getMockBuilder('FileSystem')->getMock();
        $fs->method('file_exists')->willReturn(false);
        $cache = new FileCache($fs);

        $this->assertFalse($cache->isInCache(null, null));
    }

    public function testFileIsNotInCacheWhenExistingVersionIsOlderThanOriginalFile() {
        $existingVersionPath = 'existing-version-path.jpg';
        $originalVersionPath = 'original-version-path.jpg';

        $fs = $this->getMockBuilder('FileSystem')->getMock();
        $fs->method('file_exists')->willReturn(true);
        $fs->method('lastModificationDate')
            ->withConsecutive(
                [ $this->equalTo($originalVersionPath) ],
                [ $this->equalTo($existingVersionPath) ]
            )
            ->willReturnOnConsecutiveCalls(10, 5);

        $cache = new FileCache($fs);
        $isInCache = $cache->isInCache($existingVersionPath, $originalVersionPath);
        $this->assertFalse($isInCache, 'Should not be in cache when existing version is older than the original one');
    }

    public function testFileIsAlreadyCached() {
        $existingVersionPath = 'existing-version-path.jpg';
        $originalVersionPath = 'original-version-path.jpg';

        $fs = $this->getMockBuilder('FileSystem')->getMock();
        $fs->method('file_exists')->willReturn(true);
        $fs->method('lastModificationDate')
            ->withConsecutive(
                [ $this->equalTo($originalVersionPath) ],
                [ $this->equalTo($existingVersionPath) ]
            )
            ->willReturnOnConsecutiveCalls(5, 10);

        $cache = new FileCache($fs);
        $isInCache = $cache->isInCache($existingVersionPath, $originalVersionPath);
        $this->assertTrue($isInCache, 'Should be in cache when existing version is newer than the original one');
    }

}
