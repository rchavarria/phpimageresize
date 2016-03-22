<?php

class FileCache {

    private $fileSystem;

    public function __construct(FileSystem $fs) {
        $this->fileSystem = $fs;
    }

    function isInCache($path, $imagePath) {
        if (!$this->fileSystem->file_exists($path)) {
            return false;
        }

        $origFileTime = $this->fileSystem->lastModificationDate($imagePath);
        $newFileTime = $this->fileSystem->lastModificationDate($path);
        return $newFileTime >= $origFileTime;
    }

}
