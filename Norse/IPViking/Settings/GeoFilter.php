<?php

namespace Norse\IPViking;

class Settings_GeoFilter extends Request {
    protected $_collection;
    protected $_format;

    public function __construct($config) {
        parent::__construct($config);
    }

    public function setCollection(Settings_GeoFilter_Collection $collection) {
        $this->_collection = $collection;
    }

    public function getCollection() {
        return $this->_collection;
    }

    public function setFormat($format) {
        $this->_format = $format;
    }

    public function getFormat() {
        return $this->_format;
    }

    protected function _getGeoFilterXML() {
        $collection = $this->getCollection();

        if (empty($collection)) return null;
        return $this->getCollection()->getGeoFilterXML();
    }

    protected function _getBodyFields() {
        $body_fields = parent::_getBodyFields();

        $body_fields['method']       = 'geofilter';
        $body_fields['geofilterxml'] = $this->_getGeoFilterXML();

        return $body_fields;
    }

    protected function _getEncodedBody() {
        $body_fields = $this->_getBodyFields();
        if (empty($body_fields) || !is_array($body_fields)) return null;
        return http_build_query($this->_getBodyFields(), '', '&');
    }

    protected function _getAcceptFormat() {
        if ($this->_format === 'xml') {
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
        $curl_opts[CURLOPT_POSTFIELDS] = $this->_getEncodedBody();
        $curl_opts[CURLOPT_HTTPHEADER] = $this->_getHttpHeader();

        return $curl_opts;
    }

    protected function _setCurlOpts() {
        parent::_setCurlOptArray($this->_getCurlOpts());
    }

    public function process() {
        $this->_setCurlOpts();

        $curl_response = parent::_curlExec();
        $curl_info     = parent::_curlInfo();

        return new Settings_GeoFilter_Collection($curl_response, $curl_info);
    }

    public function exec() {
        $this->_setCurlOpts();
        return parent::_curlExec();
    }

    public function getCurrentSettings() {
        return $this->process();
    }

    public function addGeoFilter(Settings_GeoFilter_Filter $filter) {
        $filter->setCommand('add');
        $this->setCollection(new Settings_GeoFilter_Collection(array($filter)));

        return $this->process();
    }

    public function deleteGeoFilter(Settings_GeoFilter_Filter $filter) {
        $filter->setCommand('delete');
        $this->setCollection(new Settings_GeoFilter_Collection(array($filter)));

        return $this->process();
    }

    public function updateGeoFilters(array $geofilters) {
        foreach ($geofilters as &$filter) {
            if (!$filter instanceof Settings_GeoFilter_Filter) {
                $filter = new Settings_GeoFilter_Filter($filter);
            }
        }

        $this->setCollection(new Settings_GeoFilter_Collection($geofilters));

        return $this->process();
    }

}
