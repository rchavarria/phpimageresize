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
            self::SCALE_KEY => false,
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
        if($this->opts[self::OUTPUT_FILENAME_KEY]) {
            return $this->opts[self::OUTPUT_FILENAME_KEY];
        }

        $widthSignal = $this->composeWidthSignal();
        $h = $this->opts[self::HEIGHT_KEY];
        $heightSignal = !empty($h) ? '_h'.$h : '';

        return $this->obtainCache() .
            $this->fileSystem->md5_file($imagePath) .
            $widthSignal .
            $heightSignal .
            $this->composeCropSignal() .
            $this->obtainScaleSignal() .
            $this->fileSystem->obtainFileExtension($imagePath);
    }

    protected function composeCropSignal() {
        if (!isset($this->opts[self::CROP_KEY])) {
            return '';
        }

        if ($this->opts[self::CROP_KEY] !== true) {
            return '';
        }

        return '_cp';
    }

    protected function obtainScaleSignal() {
        if (!isset($this->opts[self::SCALE_KEY])) {
            return '';
        }

        if ($this->opts[self::SCALE_KEY] !== true) {
            return '';
        }

        return '_sc';
    }

    protected function composeWidthSignal() {
        $w = $this->opts[self::WIDTH_KEY];
        $widthSignal = !empty($w) ? '_w' . $w : '';
        return $widthSignal;
    }

}