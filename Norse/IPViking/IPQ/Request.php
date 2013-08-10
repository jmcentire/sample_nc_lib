<?php

namespace Norse\IPViking;

class IPQ_Request extends Request {
    protected $_ip; // IPv4
    protected $_trans_id; // int
    protected $_client_id; //int
    protected $_custom_id; // varchar50
    protected $_categories; // array to ,delim
    protected $_options; // array to |delim {noresolve, url_details, supress, compress, haversine, prefix}
    protected $_address; // str
    protected $_city; // str
    protected $_zip; // str (sine qua none for geoloc)
    protected $_state; // str
    protected $_country; // char2 (sine qua none for geoloc)

    protected $_format;

    public function __construct($config, $ip) {
        parent::__construct($config);

        $this->setIP($ip);
    }

    public function setIP($ip) {
        $this->_ip = $ip;
    }

    public function getIP() {
        return $this->_ip;
    }

    public function setTransID($trans_id) {
        $this->_trans_id = $trans_id;
    }

    public function getTransID() {
        return $this->_trans_id;
    }

    public function setClientID($client_id) {
        $this->_client_id = $client_id;
    }

    public function getClientID() {
        return $this->_client_id;
    }

    public function setCustomID($custom_id) {
        $this->_custom_id = $custom_id;
    }

    public function getCustomID() {
        return $this->_custom_id;
    }

    public function setCategories(array $categories) {
        $this->_categories = $categories;
    }

    public function getCategories() {
        return $this->_categories;
    }

    public function getCategoriesString() {
        $categories = $this->getCategories();
        if (empty($categories)) return null;
        return implode(',', $categories);
    }

    public function setOptions(array $options) {
        $this->_options = $options;
    }

    public function getOptions() {
        return $this->_options;
    }

    public function getOptionsString() {
        $options = $this->getOptions();
        if (empty($options)) return null;
        return implode('|', $options);
    }

    public function setAddress($address) {
        $this->_address = $address;
    }

    public function getAddress() {
        return $this->_address;
    }

    public function setCity($city) {
        $this->_city = $city;
    }

    public function getCity() {
        return $this->_city;
    }

    public function setZip($zip) {
        $this->_zip = $zip;
    }

    public function getZip() {
        return $this->_zip;
    }

    public function setState($state) {
        $this->_state = $state;
    }

    public function getState() {
        return $this->_state;
    }

    public function setCountry($country) {
        $this->_country = $country;
    }

    public function getCountry() {
        return $this->_country;
    }

    public function setFormat($format) {
        $this->_format = $format;
    }

    public function getFormat() {
        return $this->_format;
    }

    protected function _getPostFields() {
        $post_fields = parent::_getPostFields();

        $post_fields['method']     = 'ipq';
        $post_fields['ip']         = $this->getIP();
        $post_fields['transID']    = $this->getTransID();
        $post_fields['clientID']   = $this->getClientID();
        $post_fields['customID']   = $this->getCustomID();
        $post_fields['categories'] = $this->getCategoriesString();
        $post_fields['options']    = $this->getOptionsString();
        $post_fields['address']    = $this->getAddress();
        $post_fields['city']       = $this->getCity();
        $post_fields['zip']        = $this->getZip();
        $post_fields['state']      = $this->getState();
        $post_fields['country']    = $this->getCountry();

        return $post_fields;
    }

    protected function _getEncodedPostFields() {
        return http_build_query($this->_getPostFields(), '', '&');
    }

    protected function _getAcceptFormat() {
        if ($this->getFormat() == 'xml') {
            return 'Accept: application/xml';
        }

        return 'Accept: application/json';
    }

    protected function _getHttpHeader() {
        $http_header   = parent::_getHttpHeader();
        $http_header[] = $this->_getAcceptFormat();
        return $http_header;
    }

    protected function _getCurlOpts() {
        $curl_opts = parent::_getCurlOpts();

        $curl_opts[CURLOPT_POST]       = true;
        $curl_opts[CURLOPT_POSTFIELDS] = $this->_getEncodedPostFields();
        $curl_opts[CURLOPT_HTTPHEADER] = $this->_getHttpHeader();

        return $curl_opts;
    }

    protected function _setCurlOpts() {
        parent::_setCurlOptArray($this->_getCurlOpts());
    }

    public function exec() {
        $this->_setCurlOpts();

        $curl_response = parent::_curlExec();
        $curl_info     = parent::_getCurlInfo();

        return new IPQ_Response($curl_response, $curl_info);
    }

    public function execXML() {
        $this->_setFormat('xml');
        $this->_setCurlOpts();

        return parent::_curlExec();
    }

}
