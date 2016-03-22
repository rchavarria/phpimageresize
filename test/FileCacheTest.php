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
        $isInCache = $this->exerciseIsInCache(5, 10);
        $this->assertFalse($isInCache, 'Should not be in cache when existing version is older than the original one');
    }

    public function testFileIsAlreadyCached() {
        $isInCache = $this->exerciseIsInCache(10, 5);
        $this->assertTrue($isInCache, 'Should be in cache when existing version is newer than the original one');
    }

    public function testFileIsCachedWhenExistingVersionIsSameDateAsOriginalFile() {
        $isInCache = $this->exerciseIsInCache(5, 5);
        $this->assertTrue($isInCache, 'Should be in cache when both versions are the same date');
    }

    private function exerciseIsInCache($existingVersionAge, $originalVersionAge) {
        $existingVersionPath = 'existing-version-path.jpg';
        $originalVersionPath = 'original-version-path.jpg';

        $fs = $this->getMockBuilder('FileSystem')->getMock();
        $fs->method('file_exists')->willReturn(true);
        $fs->method('lastModificationDate')
            ->withConsecutive(
                [ $this->equalTo($originalVersionPath) ],
                [ $this->equalTo($existingVersionPath) ]
            )
            ->willReturnOnConsecutiveCalls(
                $originalVersionAge,
                $existingVersionAge
            );

        $cache = new FileCache($fs);
        return $cache->isInCache($existingVersionPath, $originalVersionPath);
    }

}
