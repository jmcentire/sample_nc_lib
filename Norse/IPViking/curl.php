<?php

namespace Norse\IPViking;

if (!in_array('curl', get_loaded_extensions()) || !function_exists('curl_version')) {
    throw new Exception_Curl('cURL extension not found.  Check PHP configuration.', 182510);
}

class curl {
    protected $_handle;

    public function init($url = null) {
        $this->_handle = curl_init($url);
        return $this->_handle;
    }

    public function setOpt($option, $value) {
        return curl_setopt($this->_handle, $option, $value);
    }

    public function setOptArray(array $options) {
        return curl_setopt_array($this->_handle, $options);
    }

    public function exec() {
        return curl_exec($this->_handle);
    }

    public function getInfo() {
        return curl_getinfo($this->_handle);
    }

    public function getLastError() {
        return curl_error($this->_handle);
    }

    public function getLastErrorNo() {
        return curl_errno($this->_handle);
    }

    public function close() {
        return curl_close($this->_handle);
    }

}
