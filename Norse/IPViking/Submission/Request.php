<?php

namespace Norse\IPViking;

class Submission_Request extends Request {
    protected $_ip;
    protected $_protocol;
    protected $_cateogry;
    protected $_timestamp;

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
            throw new IPViking\Exception_InvalidSubmission('Sumission_Request::protocol values must be supplied as integers.  See documentation for a list of valid protocol ids.', 182570);
        }

        $this->_protocol = $protocol;
    }

    public function getProtocol() {
        return $this->_protocol;
    }

    public function setCategory($category) {
        if (!is_numeric($category)) {
            throw new IPViking\Exception_InvalidSubmission('Submission_Request::category values must be supplied as integers.  See documentation for a list of valid category ids.', 182571);
        }

        $this->_category = $category;
    }

    public function getCategory() {
        return $this->_category;
    }

    public function setTimestamp($timestamp) {
        if (!is_numeric($timestamp)) {
            throw new IPViking\Exception_InvalidSubmission('Submission_Request::timestamp provided is invalid; expecting timestamp, given ' . var_export($timestamp, true), 182572);
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

    protected function _getHttpHeader() {
        $http_header   = parent::_getHttpHeader();
        return $http_header;
    }

    protected function _setPutFile() {
        $body = $this->_getEncodedBody();
        $this->_filesize = strlen($body);

        // WARK curl_file_create?
        $this->_fh = fopen('php://memory', 'rw');
        fwrite($this->_fh, $body);
        rewind($this->_fh);
    }

    protected function _closePutFile() {
        if (isset($this->_fh)) {
            fclose($this->_fh);
        }
    }

    protected function _getCurlOpts() {
        $curl_opts = parent::_getCurlOpts();

        $curl_opts[CURLOPT_PUT]        = true;
        $curl_opts[CURLOPT_INFILESIZE] = $this->_filesize;
        $curl_opts[CURLOPT_INFILE]     = $this->_fh;
        $curl_opts[CURLOPT_HTTPHEADER] = $this->_getHttpHeader();

        return $curl_opts;
    }

    protected function _setCurlOpts() {
        parent::_setCurlOptArray($this->_getCurlOpts());
    }

    public function process() {
        $this->_setPutFile();
        $this->_setCurlOpts();

        $curl_response = parent::_curlExec();
        $curl_info     = parent::_curlInfo();

        $this->_closePutFile();

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