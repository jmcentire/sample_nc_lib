<?php
/**
 * An object representation of IPViking Settings GeoFilter Request data.
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

class Settings_GeoFilter extends Request {
    protected $_collection;

    public function __construct($config) {
        parent::__construct($config);
    }


    /**
     * Basic accessor methods.
     */

    public function setCollection(Settings_GeoFilter_Collection $collection) {
        $this->_collection = $collection;
    }

    public function getCollection() {
        return $this->_collection;
    }

    /**
     * Retreives XML representation of GeoFilter data.
     *
     * @return null|string XML corresponding to GeoFilter data.
     */
    protected function _getGeoFilterXML() {
        $collection = $this->getCollection();

        if (empty($collection)) return null;
        return $this->getCollection()->getGeoFilterXML();
    }


    /**
     * cURL configuration and interaction.
     */

    /**
     * @return array An array of key->value pairs to be URL encoded for requests
     */
    protected function _getBodyFields() {
        $body_fields = parent::_getBodyFields();

        $body_fields['method']       = 'geofilter';
        $body_fields['geofilterxml'] = $this->_getGeoFilterXML();

        return $body_fields;
    }

    /**
     * @return array An array of CURLOPT->value pairs for cURL configuration.
     */
    protected function _getCurlOpts() {
        $curl_opts = parent::_getCurlOpts();

        $curl_opts[CURLOPT_POST]       = true;
        $curl_opts[CURLOPT_POSTFIELDS] = $this->_getEncodedBody();
        $curl_opts[CURLOPT_HTTPHEADER] = $this->_getHttpHeader();

        return $curl_opts;
    }

    /**
     * A wrapper for curl_exec() which packages the response in a Settings_GeoFilter_Collection object.
     *
     * @return Settings_GeoFilter_Collection A response collection representing the GeoFilter response.
     */
    public function process() {
        $this->_setCurlOpts();

        $curl_response = parent::_curlExec();
        $curl_info     = parent::_curlInfo();

        return new Settings_GeoFilter_Collection($curl_response, $curl_info);
    }


    /**
     * API Methods
     */

    /**
     * @return Settings_GeoFilter_Collection
     */
    public function getCurrentSettings() {
        return $this->process();
    }

    /**
     * Adds a given filter to GeoFilter settings.
     *
     * @param Settings_GeoFilter_Filter The filter to be added.
     *
     * @return Settings_GeoFilter_Collection
     */
    public function addGeoFilter(Settings_GeoFilter_Filter $filter) {
        $filter->setCommand('add');
        $this->setCollection(new Settings_GeoFilter_Collection(array($filter)));

        return $this->process();
    }

    /**
     * Deletes the given filter from GeoFilter settings.
     *
     * @param Settings_GeoFilter_Filter The filter to be deleted.
     *
     * @return Settings_GeoFilter_Collection
     */
    public function deleteGeoFilter(Settings_GeoFilter_Filter $filter) {
        $filter->setCommand('delete');
        $this->setCollection(new Settings_GeoFilter_Collection(array($filter)));

        return $this->process();
    }

    /**
     * Update a number of GeoFilters
     *
     * @param array Array of arrays or objects representing Settings_GeoFilter_Filter data
     *
     * @return Settings_GeoFilter_Collection
     *
     * @throws Exception_InvalidGeoFilter:182581 when all elements of the $geofilters array do not have a 'command' value set.
     */
    public function updateGeoFilters(array $geofilters) {
        foreach ($geofilters as &$filter) {
            if (!$filter instanceof Settings_GeoFilter_Filter) {
                $filter = new Settings_GeoFilter_Filter($filter);
            }

            if (empty($filter->getCommand())) {
                throw new Exception_InvalidGeoFilter('Instance of Settings_GeoFilter_Filter requires valid command value.', 182581);
            }
        }

        $this->setCollection(new Settings_GeoFilter_Collection($geofilters));

        return $this->process();
    }

}
