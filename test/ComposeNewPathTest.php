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

    public function testIncludesComputedFileMD5() {
        $computedMD5 = '<md5>';
        $fs = $this->getMock('FileSystem');
        $fs->method('md5_file')->willReturn($computedMD5);
        $options = [];

        $configuration = new Configuration($options, $fs);
        $newPath = $configuration->composeNewPath('file-path.php');
        $this->assertContains($computedMD5, $newPath);
    }

    public function testKeepsFileExtension() {
        $expectedExtension = '.php';
        $fs = $this->getMock('FileSystem');
        $fs->method('obtainFileExtension')->willReturn($expectedExtension);
        $options = [];

        $configuration = new Configuration($options, $fs);
        $newPath = $configuration->composeNewPath('file-path.php');
        $this->assertContains($expectedExtension, $newPath);
    }

    public function testMarksPathAsCropped() {
        $fs = $this->getMock('FileSystem');
        $options = [ Configuration::CROP_KEY => true ];

        $configuration = new Configuration($options, $fs);
        $newPath = $configuration->composeNewPath('file-path.php');
        $this->assertContains('_cp', $newPath);
    }

    public function testDoesNotMarkPathAsCropped() {
        $fs = $this->getMock('FileSystem');
        $options = [ Configuration::CROP_KEY => false ];

        $configuration = new Configuration($options, $fs);
        $newPath = $configuration->composeNewPath('file-path.php');
        $this->assertNotContains('_cp', $newPath);
    }

    public function testMarksPathAsScaled() {
        $fs = $this->getMock('FileSystem');
        $options = [ Configuration::SCALE_KEY => true ];

        $configuration = new Configuration($options, $fs);
        $newPath = $configuration->composeNewPath('file-path.php');
        $this->assertContains('_sc', $newPath);
    }

    public function testDoesNotMarkPathAsScaled() {
        $fs = $this->getMock('FileSystem');
        $options = [ Configuration::SCALE_KEY => false ];

        $configuration = new Configuration($options, $fs);
        $newPath = $configuration->composeNewPath('file-path.php');
        $this->assertNotContains('_sc', $newPath);
    }

    public function testIncludesAWidthSignal() {
        $width = '312';
        $fs = $this->getMock('FileSystem');
        $options = [ Configuration::WIDTH_KEY => $width ];

        $configuration = new Configuration($options, $fs);
        $newPath = $configuration->composeNewPath('file-path.php');
        $this->assertContains('_w' . $width, $newPath);
    }

    public function testDoesNotIncludeAWidthSignal() {
        $fs = $this->getMock('FileSystem');
        $options = [];

        $configuration = new Configuration($options, $fs);
        $newPath = $configuration->composeNewPath('file-path.php');
        $this->assertNotContains('_w', $newPath);
    }

}
