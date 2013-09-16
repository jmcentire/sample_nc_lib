<?php
/**
 * An object representation of IPViking GeoLoc data.
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

class IPQ_GeoLoc {
	protected $_country;
	protected $_country_code;
	protected $_region;
	protected $_region_code;
	protected $_city;
	protected $_latitude;
	protected $_longitude;
	protected $_internet_service_provider;
	protected $_organization;

    public function __construct($geoLoc) {
        if (isset($geoLoc->country))                   $this->setCountry($geoLoc->country);
        if (isset($geoLoc->country_code))              $this->setCountryCode($geoLoc->country_code);
        if (isset($geoLoc->region))                    $this->setRegion($geoLoc->region);
        if (isset($geoLoc->region_code))               $this->setRegionCode($geoLoc->region_code);
        if (isset($geoLoc->city))                      $this->setCity($geoLoc->city);
        if (isset($geoLoc->latitude))                  $this->setLatitude($geoLoc->latitude);
        if (isset($geoLoc->longtitude))                $this->setLongitude($geoLoc->longtitude);
        if (isset($geoLoc->internet_service_provider)) $this->setInternetServiceProvider($geoLoc->internet_service_provider);
        if (isset($geoLoc->organization))              $this->setOrganization($geoLoc->organization);
    }


    /**
     * Basic accessor methods.
     */

    public function setCountry($country) {
        $this->_country = $country;
    }

    public function getCountry() {
        return $this->_country;
    }

    public function setCountryCode($country_code) {
        $this->_country_code = $country_code;
    }

    public function getCountryCode() {
        return $this->_country_code;
    }

    public function setRegion($region) {
        $this->_region = $region;
    }

    public function getRegion() {
        return $this->_region;
    }

    public function setRegionCode($region_code) {
        $this->_region_code = $region_code;
    }

    public function getRegionCode() {
        return $this->_region_code;
    }

    public function setCity($city) {
        $this->_city = $city;
    }

    public function getCity() {
        return $this->_city;
    }

    public function setLatitude($latitude) {
        $this->_latitude = $latitude;
    }

    public function getLatitude() {
        return $this->_latitude;
    }

    public function setLongitude($longitude) {
        $this->_longitude = $longitude;
    }

    public function getLongitude() {
        return $this->_longitude;
    }

    public function setInternetServiceProvider($internet_service_provider) {
        $this->_internet_service_provider = $internet_service_provider;
    }

    public function getInternetServiceProvider() {
        return $this->_internet_service_provider;
    }

    public function setOrganization($organization) {
        $this->_organization = $organization;
    }

    public function getOrganization() {
        return $this->_organization;
    }

}
