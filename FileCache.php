<?php

class FileCache {

    function isInCache($path, $imagePath) {
        $fs = new FileSystem();

        $isInCache = false;
        if(file_exists($path) == true):
            $isInCache = true;
            $origFileTime = $fs->lastModificationDate($imagePath);
            $newFileTime = $fs->lastModificationDate($path);
            if($newFileTime < $origFileTime): # Not using $opts['expire-time'] ??
                $isInCache = false;
            endif;
        endif;

        return $isInCache;
    }

}
