<?php

namespace Norse\IPViking;

class Settings_GeoFilter_Collection extends Response {
    protected $_geofilters;

    public function __construct($curl_response, $curl_info = null) {
        parent::__construct($curl_info);

        if (!empty($curl_response)) {
            if (is_string($curl_response)) {
                $this->_processCurlResponse($curl_response);
            } elseif (is_object($curl_response)) {
                $this->_processObject($curl_response);
            } elseif (is_array($curl_response)) {
                $this->_processArray($curl_response);
            } else {
                throw new Exception_InvalidGeoFilter('Unknown format for first argument.', 182582);
            }
        }
    }

    protected function _processCurlResponse($curl_response) {
        if (!$decoded = json_decode($curl_response)) {
            if (version_compare(PHP_VERSION, '5.5.0', '<=') && function_exists('json_last_error_msg')) {
                $json_error_msg = json_last_error_msg();
            } else {
                $json_error_msg = 'unavailable';
            }

            throw new Exception_Json('Error decoding json response: ' . $json_error_msg, json_last_error());
        }

        $this->_processArray($decoded->geofilters);
    }

    protected function _processObject($filter) {
        $this->addGeoFilter(new Settings_GeoFilter_Filter($filter));
    }

    protected function _processArray($geofilters) {
        if (empty($geofilters)) return null;

        foreach ($geofilters as $filter) {
            $this->addGeoFilter(new Settings_GeoFilter_Filter($filter));
        }
    }

    public function addGeoFilter(Settings_GeoFilter_Filter $filter) {
        $this->_geofilters[] = $filter;
    }

    public function getGeoFilters() {
        return $this->_geofilters;
    }

    public function getGeoFilterXML() {
        $geofilters = $this->getGeoFilters();

        if (empty($geofilters) || !is_array($geofilters)) {
            return null;
        }

        $geofilter_xml = <<<XML
<?xml version=1.0?>
<ipviking>
    <geofilter>
XML;

        foreach ($geofitlers as $filter) {
            $geofilter_xml .= <<<XML
        <filters>
            <command>{$filter->getCommand()}</command>
            <action>{$filter->getAction()}</action>
            <category>{$filter->getCategory()}</category>
            <country>{$filter->getCountry()}</country>
            <region>{$filter->getRegion()}</region>
            <city>{$filter->getCity()}</city>
            <zip>{$filter->getZip()}</zip>
        </filters>
XML;
        }

        $geofilter .= <<<XML
    </geofilter>
</ipviking>
XML;
        return $geofilter_xml;
    }

}
