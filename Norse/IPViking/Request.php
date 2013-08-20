<?php

namespace Norse\IPViking;

class Request {
    protected $_proxy;
    protected $_http_method;
    protected $_api_key;
<<<<<<< HEAD
    protected $_curl;

    public function __construct($config) {
        $proxy      = (isset($config['proxy']))      ? $config['proxy']      : null;
        $api_key    = (isset($config['api_key']))    ? $config['api_key']    : null;
        $curl_class = (isset($config['curl_class'])) ? $config['curl_class'] : null;

        $this->setProxy($config['proxy']);
        $this->setApiKey($config['api_key']);
        $this->_initCurl($config['curl_class']);
=======
    protected $_curl_handle;

    public function __construct($config) {
        $this->setProxy($config['proxy']);
        $this->setApiKey($config['api_key']);

        $this->_curlInit();
>>>>>>> 3885058e3555734bab4a8182ee78508587f59ebd
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

<<<<<<< HEAD
=======
    protected function _getCurlHandle() {
        return $this->_curl_handle;
    }

    protected function _setCurlHandle($ch) {
        $this->_curl_handle = $ch;
    }

>>>>>>> 3885058e3555734bab4a8182ee78508587f59ebd
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
<<<<<<< HEAD
    protected function _setCurlOpt($option, $value) {
        if (!$this->_curl->setOpt($option, $value)) {
            throw new Exception_Curl('cURL failed setopt with error: ' . $this->_curl->getLastError() . var_export(array('option' => $option, 'value' => $value), true), $this->_curl->getLastErrorNo());
=======
    protected function _setCurlOpt($opt, $val) {
        if (!curl_setopt($this->_getCurlHandle(), $opt, $val)) {
            throw new Exception_Curl('cURL failed setopt with error: ' . curl_error($this->_getCurlHandle()) . var_export(array('opt' => $opt, 'val' => $val), true), curl_errno($this->_getCurlHandle()));
>>>>>>> 3885058e3555734bab4a8182ee78508587f59ebd
        }
    }

    /**
     * WARK @throws
     */
<<<<<<< HEAD
    protected function _setCurlOptArray($options) {
        if (!$this->_curl->setOptArray($options)) {
            throw new Exception_Curl("cURL failed setopt array with error: " . $this->_curl->getLastError() . var_export($opt_array, true), $this->_curl->getLastErrorNo());
=======
    protected function _setCurlOptArray($opt_array) {
        if (!curl_setopt_array($this->_getCurlHandle(), $opt_array)) {
            throw new Exception_Curl("cURL failed setopt array with error: " . curl_error($this->_getCurlHandle()) . var_export($opt_array, true), curl_errno($this->_getCurlHandle()));
>>>>>>> 3885058e3555734bab4a8182ee78508587f59ebd
        }
    }

    /**
     * WARK @throws
     */
<<<<<<< HEAD
    protected function _initCurl($class) {
        $this->_curl = new $class();

        if (!$ch = $this->_curl->init($this->getProxy())) {
            throw new Exception_Curl('cURL failed to initialize with URL: ' . $this->getProxy(), 182510);
        }
=======
    protected function _curlInit() {
        if (!$ch = curl_init($this->getProxy())) {
            throw new Exception_Curl('cURL failed to initialize with URL: ' . $this->getProxy(), 182570);
        }

        $this->_setCurlHandle($ch);
>>>>>>> 3885058e3555734bab4a8182ee78508587f59ebd
    }

    /**
     * WARK @throws
     */
    protected function _curlInfo() {
<<<<<<< HEAD
        $info = $this->_curl->getInfo();

        if (false === $info) {
            throw new Exception_Curl('cURL getinfo failed with error: ' . $this->_curl->getLastError(), $this->_curl->getLastErrorNo());
=======
        $info = curl_getinfo($this->_getCurlHandle());

        if (false === $info) {
            throw new Exception_Curl('cURL getinfo failed with error: ' . curl_error($this->_getCurlHandle()), curl_errno($this->_getCurlHandle()));
>>>>>>> 3885058e3555734bab4a8182ee78508587f59ebd
        }

        return $info;
    }

    /**
     * WARK @throws
     */
    protected function _curlExec() {
<<<<<<< HEAD
        $result = $this->_curl->exec();

        if (false === $result) {
            throw new Exception_Curl('cURL exec failed with error: ' . $this->_curl->getLastError(), $this->_curl->getLastErrorNo());
=======
        $result = curl_exec($this->_getCurlHandle());

        if (false === $result) {
            throw new Exception_Curl('cURL exec failed with error: ' . curl_error($this->_getCurlHandle()), curl_errno($this->_getCurlHandle()));
>>>>>>> 3885058e3555734bab4a8182ee78508587f59ebd
        }

        return $result;
    }

}
