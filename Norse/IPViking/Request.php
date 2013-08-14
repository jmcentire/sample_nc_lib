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

    /**
     * WARK @throws
     */
    protected function _setCurlOpt($opt, $val) {
        if (!curl_setopt($this->_getCurlHandle(), $opt, $val)) {
            throw new Exception_Curl('cURL failed setopt with error: ' . curl_error($this->_getCurlHandle()) . var_export(array('opt' => $opt, 'val' => $val), true), curl_errno($this->_getCurlHandle()));
        }
    }

    /**
     * WARK @throws
     */
    protected function _setCurlOptArray($opt_array) {
        if (!curl_setopt_array($this->_getCurlHandle(), $opt_array)) {
            throw new Exception_Curl("cURL failed setopt array with error: " . curl_error($this->_getCurlHandle()) . var_export($opt_array, true), curl_errno($this->_getCurlHandle()));
        }
    }

    /**
     * WARK @throws
     */
    protected function _curlInit() {
        if (!$ch = curl_init($this->getProxy())) {
            throw new Exception_Curl('cURL failed to initialize with URL: ' . $this->getProxy(), 182570);
        }

        $this->_setCurlHandle($ch);
    }

    /**
     * WARK @throws
     */
    protected function _curlInfo() {
        $info = curl_getinfo($this->_getCurlHandle());

        if (false === $info) {
            throw new Exception_Curl('cURL getinfo failed with error: ' . curl_error($this->_getCurlHandle()), curl_errno($this->_getCurlHandle()));
        }

        return $info;
    }

    /**
     * WARK @throws
     */
    protected function _curlExec() {
        $result = curl_exec($this->_getCurlHandle());

        if (false === $result) {
            throw new Exception_Curl('cURL exec failed with error: ' . curl_error($this->_getCurlHandle()), curl_errno($this->_getCurlHandle()));
        }

        return $result;
    }

}
