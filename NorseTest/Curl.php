<?php

namespace NorseTest;

class Curl implements \Norse\IPViking\CurlInterface {
    protected $_data = array();

    protected function _getAPIKey() {
        foreach ($this->_data as $data) {
            if (is_string($data) && false !== stripos($data, 'apikey')) {
                $data = parse_str($data);
                if (!empty($apikey)) {
                    return $apikey;
                }
            }
        }

        return 0;
    }

    public function init($url = null) {
        if ($url == 'http://init.fail.com/') return false;

        $this->_data['url'] = $url;
        return true;
    }

    public function setOpt($option, $value) {
        if ($this->_data['url'] == 'http://setopt.fail.com/') return false;

        $this->_data[$option] = $value;

        return true;
    }

    public function setOptArray(array $options) {
        if ($this->_data['url'] == 'http://setoptarray.fail.com/') return false;

        foreach ($options as $option => $value) {
            if (!$this->setOpt($option, $value)) {
                return false;
            }
        }

        return true;
    }

    public function exec() {
        if ($this->_data['url'] == 'http://exec.fail.com/') return false;
        if ($this->_data['url'] == 'http://json.fail.com/') return 'Invalid JSON';

        return true;
    }

    public function getInfo() {
        if ($this->_data['url'] == 'http://getinfo.fail.com/')  return false;
        if ($this->_data['url'] == 'http://json.fail.com/')     return array('url' => 'http://json.fail.com/', 'content_type' => 'application/json', 'http_code' => 200);

        if ($this->_data['url'] == 'http://response.fail.com/') return array('url' => 'http://response.fail.com/', 'content_type' => 'application/json', 'http_code' => $this->_getAPIKey());

        return true;
    }

    public function getLastError() {
        if ($this->_data['url'] == 'http://exec.fail.com/')        return 'exec fail';
        if ($this->_data['url'] == 'http://getinfo.fail.com/')     return 'getinfo fail';
        if ($this->_data['url'] == 'http://setopt.fail.com/')      return 'setopt fail';
        if ($this->_data['url'] == 'http://setoptarray.fail.com/') return 'setoptarray fail';

        return '';
    }

    public function getLastErrorNo() {
        if ($this->_data['url'] == 'http://exec.fail.com/')        return 1001;
        if ($this->_data['url'] == 'http://getinfo.fail.com/')     return 1002;
        if ($this->_data['url'] == 'http://setopt.fail.com/')      return 1003;
        if ($this->_data['url'] == 'http://setoptarray.fail.com/') return 1004;

        return 0;
    }

    public function close() {
    }

}
