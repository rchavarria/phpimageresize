<?php

class FileCache {

    private $fileSystem;

    public function __construct(FileSystem $fs) {
        $this->fileSystem = $fs;
    }

    function isInCache($path, $imagePath) {
        $isInCache = false;
        if($this->fileSystem->file_exists($path) == true):
            $isInCache = true;
            $origFileTime = $this->fileSystem->lastModificationDate($imagePath);
            $newFileTime = $this->fileSystem->lastModificationDate($path);
            if($newFileTime < $origFileTime): # Not using $opts['expire-time'] ??
                $isInCache = false;
            endif;
        endif;

        return $isInCache;
    }

}
