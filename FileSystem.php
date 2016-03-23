<?php

class FileSystem {

    public function file_exists($filename) {
        return file_exists($filename);
    }

    public function file_get_contents($filename) {
        return file_get_contents($filename);
    }

    public function file_put_contents($filename, $data) {
        return file_put_contents($filename, $data);
    }

    public function filemtime($filename) {
        return filemtime($filename);
    }

    public function lastModificationDate($filepath) {
        return date("YmdHis", filemtime($filepath));
    }
    
    public function md5_file($filename) {
        return md5_file($filename);
    }
    
    public function obtainFileExtension($filename) {
        $fileInformation = pathinfo($filename);
        $ext = $fileInformation['extension'];
        return '.' . $ext;
    }
    
}