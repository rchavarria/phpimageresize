<?php

class ComposeNewPathTest extends PHPUnit_Framework_TestCase {

    public function testCanBeConfiguredWithAnOption() {
        $fs = $this->getMock('FileSystem');
        $fs->method('pathinfo')->willReturn([ 'extension' => 'php' ]);
        $expectedPath = 'new-path-to-the-heaven.php';
        $options = [ 'output-filename' => $expectedPath ];

        $configuration = new Configuration($options, $fs);
        $newPath = $configuration->composeNewPath('some-other-file-path.php');
        $this->assertEquals($expectedPath, $newPath);
    }

}
