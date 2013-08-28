<?php

namespace Norse\IPViking;

/**
 * Base object managing the interface with the IPViking API.
 */
class Request {
    /* Target URL for IPViking's API */
    protected $_proxy;

    /* The HTTP Method to be attempted */
    protected $_http_method;

    /* The user's API Key for the IPViking API */
    protected $_api_key;

    /* An instance of Norse\IPViking\CurlInterface */
    protected $_curl;

    /* The response format requested {xml/json} */
    protected $_format;

    /**
     * The constructor sets up key elements of the connection the IPViking API.
     *
     * @param array $config Configuration values for proxy, api_key, and curl.
     *
     * @throws Exception_InvalidRequest:182534 when the supplied config is missing a proxy URL value.
     * @throws Exception_InvalidRequest:182535 when the supplied config is missing an API Key value.
     * @throws Exception_InvalidRequest:182536 when the supplied config is missing a cURL object.
     */
    public function __construct(array $config) {
        if (isset($config['proxy'])) {
            $proxy = $config['proxy'];
        } else {
            throw new Exception_InvalidRequest('Proxy URL required for Request class functionality.', 182534);
        }

        if (isset($config['api_key'])) {
            $api_key = $config['api_key'];
        } else {
            throw new Exception_InvalidRequest('API Key required for Request class functionality.', 182535);
        }

        if (isset($config['curl'])) {
            $curl = $config['curl'];
        } else {
            throw new Exception_InvalidRequest('cURL object required for Request class functionality.', 182536);
        }

        $this->setProxy($proxy);
        $this->setApiKey($api_key);
        $this->setCurl($curl);
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

    /**
     * WARK @throws
     */
    public function setCurl($curl) {
        if (!$curl instanceof CurlInterface) {
            throw new Exception_Curl('cURL object provided does not implement \Norse\IPViking\CurlInterface.', 182511);
        }

        $this->_curl = $curl;

        if (!$ch = $this->_curl->init($this->getProxy())) {
            throw new Exception_Curl('cURL init failed with URL: ' . $this->getProxy(), 182512);
        }
    }

    public function setFormat($format) {
        $this->_format = $format;
    }

    public function getFormat() {
        return $this->_format;
    }

    protected function _getAcceptFormat() {
        if ($this->getFormat() === 'xml') {
            return 'Accept: application/xml';
        }

        return 'Accept: application/json';
    }


    protected function _getBodyFields() {
        return array(
            'apikey' => $this->getApiKey(),
        );
    }

    protected function _getEncodedBody() {
        $body_fields = $this->_getBodyFields();
        if (empty($body_fields) || !is_array($body_fields)) return null;
        return http_build_query($this->_getBodyFields(), '', '&');
    }

    protected function _getHttpHeader() {
        return array(
            'Content-Type:  application/x-www-form-urlencoded',
            $this->_getAcceptFormat(),
        );
    }

    protected function _getCurlOpts() {
        return array(
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_RETURNTRANSFER => true,
        );
    }

    protected function _setCurlOpts() {
        $this->_setCurlOptArray($this->_getCurlOpts());
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


    public function exec() {
        $this->_setCurlOpts();
        return $this->_curlExec();
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
