<?php

namespace Norse\IPViking;

class Request {
    protected $_proxy;
    protected $_http_method;
    protected $_api_key;
    protected $_curl;

    public function __construct($config) {
        $proxy   = (isset($config['proxy']))   ? $config['proxy']   : null;
        $api_key = (isset($config['api_key'])) ? $config['api_key'] : null;
        $curl    = (isset($config['curl']))    ? $config['curl']    : null;

        $this->setProxy($config['proxy']);
        $this->setApiKey($config['api_key']);
        $this->setCurl($config['curl']);
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
    protected function _setCurlOpt($option, $value) {
        if (!$this->_curl->setOpt($option, $value)) {
            throw new Exception_Curl('cURL setopt failed with error: ' . $this->_curl->getLastError(), $this->_curl->getLastErrorNo());
        }
    }

    /**
     * WARK @throws
     */
    protected function _setCurlOptArray($options) {
        if (!$this->_curl->setOptArray($options)) {
            throw new Exception_Curl("cURL setopt array failed with error: " . $this->_curl->getLastError(), $this->_curl->getLastErrorNo());
        }
    }

    /**
     * WARK @throws
     */
    protected function setCurl($curl) {
        $this->_curl = $curl;

        if (!$ch = $this->_curl->init($this->getProxy())) {
            throw new Exception_Curl('cURL init failed with URL: ' . $this->getProxy(), 182511);
        }
    }

    /**
     * WARK @throws
     */
    protected function _curlInfo() {
        $info = $this->_curl->getInfo();

        if (false === $info) {
            throw new Exception_Curl('cURL getinfo failed with error: ' . $this->_curl->getLastError(), $this->_curl->getLastErrorNo());
        }

        return $info;
    }

    /**
     * WARK @throws
     */
    protected function _curlExec() {
        $result = $this->_curl->exec();

        if (false === $result) {
            throw new Exception_Curl('cURL exec failed with error: ' . $this->_curl->getLastError(), $this->_curl->getLastErrorNo());
        }

        return $result;
    }

}
