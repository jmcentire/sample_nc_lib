<?php

namespace Norse\IPViking;

class IPQ_Response {
    protected $_ip;
    protected $_trans_id;
    protected $_client_id;
    protected $_custom_id;
    protected $_method;
    protected $_host;
    protected $_risk_factor;
    protected $_risk_color;
    protected $_risk_name;
    protected $_risk_desc;
    protected $_timestamp;
    protected $_factor_entries_count;

    /* IPQ_IPInfo */
    protected $_ip_info;

    /* IPQ_GeoLoc */
    protected $_geo_loc;

    /* IPQ_Entry[] */
    protected $_entries;

    /* IPQ_FactoringReason */
    protected $_factoring;

    public function __construct($curl_response, $curl_info) {
        $curl_response = '{
            "response": {
                "method": "ipq",
                "ip": "216.38.154.18",
                "host": "mail.rpaarch.com",
                "clientID": 0,
                "transID": 0,
                "customID": 0,
                "risk_factor": 0,
                "risk_color": "green",
                "risk_name": "Low",
                "risk_desc": "Low risk involved",
                "timestamp": "2013-08-09T17:21:25-04:00",
                "factor_entries": 13,
                "ip_info": {
                    "autonomous_system_number": "n/a",
                    "autonomous_system_name": "n/a"
                },
                "geoloc": {
                    "country": "United States",
                    "country_code": "US",
                    "region": "CALIFORNIA",
                    "region_code": "CA",
                    "city": "San Francisco",
                    "latitude": "37.7749",
                    "longtitude": "-122.419",
                    "internet_service_provider": "Fastmetrics",
                    "organization": "PS Print"
                },
                "entries": [
                    {
                        "category_name": "Botnet",
                        "category_id": "5",
                        "category_factor": "65",
                        "protocol_id": "41",
                        "last_active": "2013-06-04T20:39:39-04:00",
                        "overall_protocol": "Botnet",
                        "protocol_name": "Bot"
                    },
                    {
                        "category_name": "Global Whitelist",
                        "category_id": "71",
                        "category_factor": "-100",
                        "protocol_id": "381",
                        "last_active": "2013-02-14T19:18:37-05:00",
                        "overall_protocol": "whitelist",
                        "protocol_name": "Manual WL entry"
                    }
                ],
                "factoring": [
                    {
                        "country_risk_factor": "4.1",
                        "region_risk_factor": "5",
                        "ip_resolve_factor": "-2",
                        "asn_record_factor": "10",
                        "asn_threat_factor": 5,
                        "bgp_delegation_factor": "-2",
                        "iana_allocation_factor": "-2",
                        "ipviking_personal_factor": "-1",
                        "ipviking_category_factor": -103,
                        "ipviking_geofilter_factor": "-50",
                        "ipviking_geofilter_rule": "423",
                        "data_age_factor": "3",
                        "geomatch_distance": 0,
                        "geomatch_factor": 0,
                        "search_volume_factor": "0"
                    }
                ]
            }
        }';
        $curl_info = array(
            "url"                       => "http://beta.ipviking.com/api/",
            "content_type"              => "application/json",
            "http_code"                 => 302,
            "header_size"               => 268,
            "request_size"              => 243,
            "filetime"                  => -1,
            "ssl_verify_result"         => 0,
            "redirect_count"            => 0,
            "total_time"                => 1.504347,
            "namelookup_time"           => 0.000529,
            "connect_time"              => 0.098011,
            "pretransfer_time"          => 0.098127,
            "size_upload"               => 99,
            "size_download"             => 1393,
            "speed_download"            => 925,
            "speed_upload"              => 65,
            "download_content_length"   => 1393,
            "upload_content_length"     => 99,
            "starttransfer_time"        => 1.275935,
            "redirect_time"             => 0,
            "certinfo"                  => array(),
            "redirect_url"              => "",
        );
        if ($curl_info['http_code'] !== 302) {
            throw new Exception('Bad Response');
        }

        $this->_processCurlResponse($curl_response);
    }

    protected function _processCurlResponse($curl_response) {
        if (!$decoded = json_decode($curl_response)) {
            throw new Exception('Cannot parse cURL response: ' . json_last_error());
        }
        $response = $decoded->response;

        $this->setIP($response->ip);
        $this->setTransID($response->transID);
        $this->setClientID($response->clientID);
        $this->setCustomID($response->customID);
        $this->setMethod($response->method);
        $this->setHost($response->host);
        $this->setRiskFactor($response->risk_factor);
        $this->setRiskColor($response->risk_color);
        $this->setRiskName($response->risk_name);
        $this->setRiskDesc($response->risk_desc);
        $this->setTimestamp($response->timestamp);
        $this->setFactorEntriesCount($response->factor_entries);

        $this->setIPInfo($response->ip_info);
        $this->setGeoLoc($response->geoLoc);
        $this->setEntries($response->entries);
        $this->setFactoring($response->factoring);
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
        return $this->client_id;
    }

    public function setCustomID($custom_id) {
        $this->_custom_id = $custom_id;
    }

    public function getCustomID() {
        return $this->_custom_id;
    }

    public function setMethod($method) {
        $this->_method = $method;
    }

    public function getMethod() {
        return $this->_method;
    }

    public function setHost($host) {
        $this->_host = $host;
    }

    public function getHost() {
        return $this->_host;
    }

    public function setRiskFactor($risk_factor) {
        $this->_risk_factor = $risk_factor;
    }

    public function getRiskFactor() {
        return $this->_risk_factor;
    }

    public function setRiskColor($risk_color) {
        $this->_risk_color = $risk_color;
    }

    public function getRiskColor() {
        return $this->_risk_color;
    }

    public function setRiskName($risk_name) {
        $this->_risk_name = $risk_name;
    }

    public function getRiskName() {
        return $this->_risk_name;
    }

    public function setRiskDesc($risk_desc) {
        $this->_risk_desc = $risk_desc;
    }

    public function getRiskDesc() {
        return $this->_risk_desc;
    }

    public function setTimestamp($timestamp) {
        $this->_timestamp = $timestamp;
    }

    public function getTimestamp() {
        return $this->_timestamp;
    }

    public function setFactorEntriesCount($factor_entries) {
        $this->_factor_entries_count = $factor_entries;
    }

    public function getFactorEntriesCount() {
        return $this->_factor_entries_count;
    }

    public function setIPInfo($ip_info) {
        $this->_ip_info = new IPQ_IPInfo($ip_info);
    }

    /* IPQ_IPInfo */
    public function getIPInfo() {
        return $this->_ip_info;
    }

    public function setGeoLoc($geoLoc) {
        $this->_geo_loc = new IPQ_GeoLoc($geoLoc);
    }

    /* IPQ_GeoLoc */
    public function getGeoLoc() {
        return $this->_geo_loc;
    }

    public function setEntries($entries) {
        if (empty($entries) || !is_array($entries)) return;
        foreach ($entries as $entry) {
            $this->_entries[] = new IPQ_Entry($entry);
        }
    }

    /* IPQ_Entry[] */
    public function getEntries() {
        return $this->_entries;
    }

    public function setFactoring($factoring) {
        if (empty($factoring) || !is_array($factoring)) return;
        foreach ($factoring as $reason) {
            $this->_factoring[] = new IPQ_FactoringReason($reason);
        }
    }

    /* IPQ_FactoringReason */
    public function getFactoringReason() {
        return $this->_factoring;
    }

}
