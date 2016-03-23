<?php

class Configuration {
    const CACHE_PATH = './cache/';
    const REMOTE_PATH = './cache/remote/';

    const CACHE_KEY = 'cacheFolder';
    const REMOTE_KEY = 'remoteFolder';
    const CACHE_MINUTES_KEY = 'cache_http_minutes';
    const WIDTH_KEY = 'width';
    const HEIGHT_KEY = 'height';

    const CONVERT_PATH = 'convert';

    private $opts;
    private $fileSystem;

    public function __construct($opts = [], FileSystem $fileSystem = null) {
        $sanitized= $this->sanitize($opts);

        $defaults = array(
            'crop' => false,
            'scale' => 'false',
            'thumbnail' => false,
            'maxOnly' => false,
            'canvas-color' => 'transparent',
            'output-filename' => false,
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
        $w = $this->obtainWidth();
        $h = $this->obtainHeight();
        $filename = $this->fileSystem->md5_file($imagePath);
        $extension = $this->fileSystem->obtainFileExtension($imagePath);

        $cropSignal = isset($this->opts['crop']) && $this->opts['crop'] == true ? "_cp" : "";
        $scaleSignal = isset($this->opts['scale']) && $this->opts['scale'] == true ? "_sc" : "";
        $widthSignal = !empty($w) ? '_w'.$w : '';
        $heightSignal = !empty($h) ? '_h'.$h : '';

        $newPath = $this->obtainCache() .$filename.$widthSignal.$heightSignal.$cropSignal.$scaleSignal.$extension;

        if($this->opts['output-filename']) {
            $newPath = $this->opts['output-filename'];
        }

        return $newPath;
    }
    
}