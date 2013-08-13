<?php

namespace Norse\IPViking;

class Request {
    protected $_proxy;
    protected $_http_method;
    protected $_api_key;
    protected $_curl_handle;

    public function __construct($config) {
        $this->setProxy($config['proxy']);
        $this->setApiKey($config['api_key']);

        $this->_curlInit();
    }

    public function setProxy($proxy) {
        $this->_proxy = $proxy;
    }

    public function getProxy() {
        return $this->_proxy;
    }

    public function setApiKey($api_key) {
        $this->_api_key = $api_key;
    }

    public function getApiKey() {
        return $this->_api_key;
    }

    protected function _getCurlHandle() {
        return $this->_curl_handle;
    }

    protected function _setCurlHandle($ch) {
        $this->_curl_handle = $ch;
    }

    protected function _getBodyFields() {
        return array(
            'apikey' => $this->getApiKey(),
        );
    }

    protected function _getHttpHeader() {
        return array(
            'Content-Type:  application/x-www-form-urlencoded',
        );
    }

    protected function _getCurlOpts() {
        return array(
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_RETURNTRANSFER => true,
        );
    }

    protected function _setCurlOpt($opt, $val) {
        return curl_setopt($this->_getCurlHandle(), $opt, $val);
    }

    protected function _setCurlOptArray($opt_array) {
        return curl_setopt_array($this->_getCurlHandle(), $opt_array);
    }

    protected function _curlInit() {
        $this->_setCurlHandle(curl_init($this->getProxy()));
    }

    protected function _getCurlInfo() {
        return curl_getinfo($this->_getCurlHandle());
    }

    protected function _curlExec() {
        return curl_exec($this->_getCurlHandle());
    }

}
