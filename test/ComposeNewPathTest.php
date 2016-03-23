<?php

class ComposeNewPathTest extends PHPUnit_Framework_TestCase {

    public function testCanBeConfiguredWithAnOption() {
        $fs = $this->getMock('FileSystem');
        $expectedPath = 'new-path-to-the-heaven.php';
        $options = [ Configuration::OUTPUT_FILENAME_KEY => $expectedPath ];

        $configuration = new Configuration($options, $fs);
        $newPath = $configuration->composeNewPath('file-path.php');
        $this->assertEquals($expectedPath, $newPath);
    }

    public function testIncludesConfiguredCachePath() {
        $fs = $this->getMock('FileSystem');
        $cachePath = 'cache-path';
        $options = [ Configuration::CACHE_KEY => $cachePath ];

        $configuration = new Configuration($options, $fs);
        $newPath = $configuration->composeNewPath('file-path.php');
        $this->assertContains($cachePath, $newPath);
    }

}
