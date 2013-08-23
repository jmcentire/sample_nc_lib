<?php

namespace Norse\IPViking;

class Submission_Request extends Request {
    protected $_ip;
    protected $_protocol;
    protected $_cateogry;
    protected $_timestamp;

    protected $_format;

    public function __construct($config, $ip, $protocol, $category, $timestamp) {
        parent::__construct($config);

        $this->setIP($ip);
        $this->setProtocol($protocol);
        $this->setCategory($category);
        $this->setTimestamp($timestamp);
    }

    public function setIP($ip) {
        $this->_ip = $ip;
    }

    public function getIP() {
        return $this->_ip;
    }

    public function setProtocol($protocol) {
        if (!is_numeric($protocol)) {
            throw new Exception_InvalidSubmission('Submission_Request::protocol values must be supplied as integers.  See documentation for a list of valid protocol ids.', 182570);
        }

        $this->_protocol = $protocol;
    }

    public function getProtocol() {
        return $this->_protocol;
    }

    public function setCategory($category) {
        if (!is_numeric($category)) {
            throw new Exception_InvalidSubmission('Submission_Request::category values must be supplied as integers.  See documentation for a list of valid category ids.', 182571);
        }

        $this->_category = $category;
    }

    public function getCategory() {
        return $this->_category;
    }

    public function setTimestamp($timestamp) {
        if (!is_numeric($timestamp)) {
            throw new Exception_InvalidSubmission('Submission_Request::timestamp provided is invalid; expecting timestamp, given ' . var_export($timestamp, true), 182572);
        }

        $this->_timestamp = $timestamp;
    }

    public function getTimestamp() {
        return $this->_timestamp;
    }

    protected function _getBodyFields() {
        $body_fields = parent::_getBodyFields();

        $body_fields['method']    = 'submission';
        $body_fields['ip']        = $this->getIP();
        $body_fields['protocol']  = $this->getProtocol();
        $body_fields['category']  = $this->getCategory();
        $body_fields['timestamp'] = $this->getTimestamp();

        return $body_fields;
    }

    protected function _getEncodedBody() {
        return http_build_query($this->_getBodyFields(), '', '&');
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

    protected function _getHttpHeader() {
        $http_header   = parent::_getHttpHeader();
        $http_header[] = $this->_getAcceptFormat();
        $http_header[] = 'Expect: ';
        return $http_header;
    }

    protected function _setCurlFile() {
        $body = $this->_getEncodedBody();
        $this->_filesize = strlen($body);

        $this->_file = fopen('php://memory', 'w+b');
        fwrite($this->_file, $body);
        rewind($this->_file);
    }

    protected function _closeCurlFile() {
        fclose($this->_file);
    }

    protected function _getCurlOpts() {
        $curl_opts = parent::_getCurlOpts();

        $curl_opts[CURLOPT_PUT]        = true;
        $curl_opts[CURLOPT_INFILESIZE] = $this->_filesize;
        $curl_opts[CURLOPT_INFILE]     = $this->_file;
        $curl_opts[CURLOPT_HTTPHEADER] = $this->_getHttpHeader();

        return $curl_opts;
    }

    protected function _setCurlOpts() {
        parent::_setCurlOptArray($this->_getCurlOpts());
    }

    public function process() {
        $this->_setCurlFile();
        $this->_setCurlOpts();

        $curl_response = parent::_curlExec();
        $curl_info     = parent::_curlInfo();

        $this->_closeCurlFile();
        return new Submission_Response($curl_response, $curl_info);
    }

    public function exec() {
        $this->_setPutFile();
        $this->_setCurlOpts();

        $curl_response = parent::_curlExec();

        $this->_closePutFile();

        return $curl_response;
    }

}
