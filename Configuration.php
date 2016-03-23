<?php

class Configuration {
    const CACHE_PATH = './cache/';
    const REMOTE_PATH = './cache/remote/';

    const CACHE_KEY = 'cacheFolder';
    const REMOTE_KEY = 'remoteFolder';
    const CACHE_MINUTES_KEY = 'cache_http_minutes';
    const WIDTH_KEY = 'width';
    const HEIGHT_KEY = 'height';
    const CROP_KEY = 'crop';
    const SCALE_KEY = 'scale';
    const OUTPUT_FILENAME_KEY = 'output-filename';

    const CONVERT_PATH = 'convert';

    private $opts;
    private $fileSystem;

    public function __construct($opts = [], FileSystem $fileSystem = null) {
        $sanitized= $this->sanitize($opts);

        $defaults = array(
            self::CROP_KEY => false,
            self::SCALE_KEY => 'false',
            'thumbnail' => false,
            'maxOnly' => false,
            'canvas-color' => 'transparent',
            self::OUTPUT_FILENAME_KEY => false,
            self::CACHE_KEY => self::CACHE_PATH,
            self::REMOTE_KEY => self::REMOTE_PATH,
            'quality' => 90,
            'cache_http_minutes' => 20,
            'width' => null,
            'height' => null);

        $this->opts = array_merge($defaults, $sanitized);
        
        $this->fileSystem = $fileSystem;
        if ($fileSystem === null) {
            $this->fileSystem = new FileSystem();
        }
    }

    public function asHash() {
        return $this->opts;
    }

    public function obtainCache() {
        return $this->opts[self::CACHE_KEY];
    }

    public function obtainRemote() {
        return $this->opts[self::REMOTE_KEY];
    }

    public function obtainConvertPath() {
        return self::CONVERT_PATH;
    }

    public function obtainWidth() {
        return $this->opts[self::WIDTH_KEY];
    }

    public function obtainHeight() {
        return $this->opts[self::HEIGHT_KEY];
    }

    public function obtainCacheMinutes() {
        return $this->opts[self::CACHE_MINUTES_KEY];
    }
    private function sanitize($opts) {
        if($opts == null) return array();

        return $opts;
    }
    
    public function composeNewPath($imagePath) {
        $w = $this->opts[self::WIDTH_KEY];
        $h = $this->opts[self::HEIGHT_KEY];
        $filename = $this->fileSystem->md5_file($imagePath);
        $extension = $this->fileSystem->obtainFileExtension($imagePath);

        $cropSignal = isset($this->opts[self::CROP_KEY]) && $this->opts[self::CROP_KEY] == true ? "_cp" : "";
        $scaleSignal = isset($this->opts[self::SCALE_KEY]) && $this->opts[self::SCALE_KEY] == true ? "_sc" : "";
        $widthSignal = !empty($w) ? '_w'.$w : '';
        $heightSignal = !empty($h) ? '_h'.$h : '';

        $newPath = $this->obtainCache() .$filename.$widthSignal.$heightSignal.$cropSignal.$scaleSignal.$extension;

        if($this->opts[self::OUTPUT_FILENAME_KEY]) {
            $newPath = $this->opts[self::OUTPUT_FILENAME_KEY];
        }

        return $newPath;
    }
    
}