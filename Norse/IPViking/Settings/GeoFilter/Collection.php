<?php
/**
 * An object representation of a collection of IPViking Settings GeoFilter Request/Response data.
 *
 * @package Norse
 * @subpackage IPViking
 * @author Jeremy McEntire
 * @version 1.0
 *
 * Copyright (c) 2013, Norse Corp
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in the
 *     documentation and/or other materials provided with the distribution.
 *   * Neither the name of the <organization> nor the
 *     names of its contributors may be used to endorse or promote products
 *     derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace Norse\IPViking;

class Settings_GeoFilter_Collection extends Response {
    protected $_geofilters;

    /**
     * The constructor accepts either a cURL response and cURL Info array or another data
     * structure representing GeoFilter Filter data.
     *
     * @param string $curl_response The JSON response from a curl request.
     * @param array $curl_info The array result of curl_getinfo()
     *
     * @param mixed $curl_response An array of GeoFilter data or an object of GeoFitler data.
     * @param null $curl_info
     *
     * @throws Exception_InvalidGeoFilter:182582 when the data type of $curl_response is unknown.
     */
    public function __construct($curl_response, $curl_info = null) {
        if (null !== $curl_info) parent::__construct($curl_response, $curl_info);

        if (!empty($curl_response) && $curl_response !== 'null') {
            if (is_string($curl_response)) {
                $this->_processCurlResponse($curl_response);
            } elseif (is_object($curl_response)) {
                $this->_processObject($curl_response);
            } elseif (is_array($curl_response)) {
                $this->_processArray($curl_response);
            } else {
                throw new Exception_InvalidGeoFilter('Unknown format for first argument of Settings_GeoFilter_Collection constructor.', 182582);
            }
        }
    }

    protected function _processCurlResponse($curl_response) {
        if (!$decoded = json_decode($curl_response)) {
            if (version_compare(PHP_VERSION, '5.5.0', '>=') && function_exists('json_last_error_msg')) {
                $json_error_msg = json_last_error_msg();
            } else {
                $json_error_msg = 'unavailable';
            }

            throw new Exception_Json('Error decoding json response: ' . var_export(array('response' => $curl_response, 'json_error_msg' => $json_error_msg), true), json_last_error());
        }

        if (!isset($decoded->geofilters)) {
            throw new Exception_UnexpectedResponse('Expecting element \'geofilters\' in response: ' . var_export($decoded, true), 182541);
        }

        if (is_array($decoded->geofilters)) {
            $this->_processArray($decoded->geofilters);
        } elseif (is_object($decoded->geofilters)) {
            $this->_processObject($decoded->geofilters);
        } else {
            throw new Exception_UnexpectedResponse('Unexpected data type for \'geofilters\' in response: ' . var_export($decoded->geofilters, true), 182542);
        }
    }

    protected function _processObject($filter) {
        if (!$filter instanceof Norse\IPViking\Settings_GeoFilter_Filter) {
            $filter = new Settings_GeoFilter_Filter($filter);
        }

        $this->addGeoFilter(new Settings_GeoFilter_Filter($filter));
    }

    protected function _processArray($geofilters) {
        if (empty($geofilters)) return null;

        foreach ($geofilters as $filter) {
            $this->addGeoFilter(new Settings_GeoFilter_Filter($filter));
        }
    }

    /**
     * Add a Settings_GeoFilter_Filter object to the collection.
     */
    public function addGeoFilter(Settings_GeoFilter_Filter $filter) {
        $this->_geofilters[] = $filter;
    }

    public function getGeoFilters() {
        return $this->_geofilters;
    }

    /**
     * Returns the data of the collection as XML.
     *
     * @return null|string An XML representation of the GeoFilter data.
     */
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

        foreach ($geofilters as $filter) {
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

        $geofilter_xml .= <<<XML
    </geofilter>
</ipviking>
XML;
        return $geofilter_xml;
    }

}
