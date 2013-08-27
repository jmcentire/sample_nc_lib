<?php

namespace NorseTest;

class Curl implements \Norse\IPViking\CurlInterface {
    protected $_data = array();

    protected function _getAPIKey() {
        foreach ($this->_data as $data) {
            if (is_string($data) && false !== stripos($data, 'apikey')) {
                $data = parse_str($data);
                if (!empty($apikey)) {
                    return $apikey;
                }
            }
        }

        return 0;
    }

    protected function _getIPQCurlInfo() {
        return array(
            'url' => 'http://ipq.test.com/',
            'content_type' => 'application/json',
            'http_code' => 302,
        );
    }

    protected function _getResponseFailCurlInfo() {
        return array(
            'url' => 'http://response.fail.com/',
            'content_type' => 'application/json',
            'http_code' => $this->_getAPIKey(),
        );
    }

    protected function _getSubmissionCurlInfo() {
        return array(
            'url' => 'http://submission.test.com/',
            'content_type' => 'application/json',
            'http_code' => 201,
        );
    }

    protected function _getGeoFilterCurlInfo() {
        return array(
            'url' => 'http://geofilter.test.com/',
            'content_type' => 'application/json',
            'http_code' => 302,
        );
    }

    protected function _getRiskFactorCurlInfo() {
        return array(
            'url' => 'http://riskfactor.test.com/',
            'contnet_type' => 'application/json',
            'http_code' => 302,
        );
    }

    protected function _getIPQJsonResponse() {
        return '{
    "response": {
        "method": "ipq",
        "ip": "67.13.46.123",
        "host": "NXDOMAIN",
        "clientID": 0,
        "transID": 0,
        "customID": 0,
        "risk_factor": 83,
        "risk_color": "orange",
        "risk_name": "High",
        "risk_desc": "High risk Involved",
        "timestamp": "2013-08-22T18:50:31-04:00",
        "factor_entries": 13,
        "ip_info": {
            "autonomous_system_number": "n\/a",
            "autonomous_system_name": "n\/a"
        },
        "geoloc": {
            "country": "United States",
            "country_code": "US",
            "region": "-",
            "region_code": "-",
            "city": "-",
            "latitude": "38",
            "longtitude": "-97",
            "internet_service_provider": "Century Link",
            "organization": "Century Link"
        },
        "entries": [
            {
                "category_name": "Bogon Unadv",
                "category_id": "2",
                "category_factor": "25",
                "protocol_id": "31",
                "last_active": "2013-08-16T04:31:07-04:00",
                "overall_protocol": "Unadvertised IP",
                "protocol_name": "IP unadvertised"
            }
        ],
        "factoring": [
            {
                "country_risk_factor": "4.1",
                "region_risk_factor": "0",
                "ip_resolve_factor": "8",
                "asn_record_factor": "10",
                "asn_threat_factor": 5,
                "bgp_delegation_factor": "20",
                "iana_allocation_factor": "-2",
                "ipviking_personal_factor": "-1",
                "ipviking_category_factor": 19,
                "ipviking_geofilter_factor": 0,
                "ipviking_geofilter_rule": 0,
                "data_age_factor": "20",
                "geomatch_distance": 0,
                "geomatch_factor": 0,
                "search_volume_factor": "0"
            }
        ]
    }
}';
    }

    protected function _getSubmissionJsonResponse() {
        return null;
    }

    protected function _getGeoFilterJsonResponse() {
        return '{
    "geofilters": [
        {
            "filter_id": "443",
            "action": "Allow",
            "clientID": "0",
            "category": "City",
            "country": "TW",
            "region": "04",
            "city": "PONG",
            "zip": "-",
            "hits": "0"
        },
        {
            "filter_id": "423",
            "action": "Allow",
            "clientID": "0",
            "category": "Country",
            "country": "US",
            "region": "-",
            "city": "-",
            "zip": "-",
            "hits": "4140"
        },
        {
            "filter_id": "433",
            "action": "Deny",
            "clientID": "0",
            "category": "Master",
            "country": "-",
            "region": "-",
            "city": "-",
            "zip": "-",
            "hits": "0"
        },
        {
            "filter_id": "1031",
            "action": "Allow",
            "clientID": "0",
            "category": "Master",
            "country": "-",
            "region": "-",
            "city": "-",
            "zip": "-",
            "hits": "0"
        }
    ]
}';
    }

    protected function _getRiskFactorJsonResponse() {
        $json = '{"settings":[{"risk_id":"1","risk_attribute":"Country Risk Factor","risk_good_value":"99","risk_bad_value":"99"},{"risk_id":"2","risk_attribute":"Region Risk Factor","risk_good_value":"99","risk_bad_value":"99"},{"risk_id":"3","risk_attribute":"IP resolve Factor","risk_good_value":"-2","risk_bad_value":"8"},{"risk_id":"4","risk_attribute":"ASN Risk Factor","risk_good_value":"-2","risk_bad_value":"10"},{"risk_id":"5","risk_attribute":"BGP Status Risk Factor","risk_good_value":"-2","risk_bad_value":"20"},{"risk_id":"6","risk_attribute":"IANA status Risk factor","risk_good_value":"-2","risk_bad_value":"10"},{"risk_id":"7","risk_attribute":"ByteWolf Risk factor","risk_good_value":"-1","risk_bad_value":"50"},{"risk_id":"8","risk_attribute":"Category Risk Factor","risk_good_value":"99","risk_bad_value":"99"},{"risk_id":"9","risk_attribute":"Freshness Risk Factor","risk_good_value":"-15","risk_bad_value":"20"},{"risk_id":"10","risk_attribute":"Search Volume","risk_good_value":"0","risk_bad_value":"20"},{"risk_id":"11","risk_attribute":"GeoFilter Factor","risk_good_value":"-50","risk_bad_value":"99"}]}';
    }

    public function init($url = null) {
        if ($url == 'http://init.fail.com/') return false;

        $this->_data['url'] = $url;
        return true;
    }

    public function setOpt($option, $value) {
        if ($this->_data['url'] == 'http://setopt.fail.com/') return false;

        $this->_data[$option] = $value;

        return true;
    }

    public function setOptArray(array $options) {
        if ($this->_data['url'] == 'http://setoptarray.fail.com/') return false;

        foreach ($options as $option => $value) {
            if (!$this->setOpt($option, $value)) {
                return false;
            }
        }

        return true;
    }

    public function exec() {
        if ($this->_data['url'] == 'http://exec.fail.com/')       return false;
        if ($this->_data['url'] == 'http://json.fail.com/')       return 'Invalid JSON';
        if ($this->_data['url'] == 'http://ipq.test.com/')        return $this->_getIPQJsonResponse();
        if ($this->_data['url'] == 'http://submission.test.com/') return $this->_getSubmissionJsonResponse();
        if ($this->_data['url'] == 'http://geofilter.test.com/')  return $this->_getGeoFilterJsonResponse();

        return true;
    }

    public function getInfo() {
        if ($this->_data['url'] == 'http://getinfo.fail.com/')    return false;
        if ($this->_data['url'] == 'http://json.fail.com/')       return $this->_getIPQCurlInfo();
        if ($this->_data['url'] == 'http://response.fail.com/')   return $this->_getResponseFailCurlInfo();
        if ($this->_data['url'] == 'http://ipq.test.com/')        return $this->_getIPQCurlInfo();
        if ($this->_data['url'] == 'http://submission.test.com/') return $this->_getSubmissionCurlInfo();
        if ($this->_data['url'] == 'http://geofilter.test.com/')  return $this->_getGeoFilterCurlInfo();

        return true;
    }

    public function getLastError() {
        if ($this->_data['url'] == 'http://exec.fail.com/')        return 'exec fail';
        if ($this->_data['url'] == 'http://getinfo.fail.com/')     return 'getinfo fail';
        if ($this->_data['url'] == 'http://setopt.fail.com/')      return 'setopt fail';
        if ($this->_data['url'] == 'http://setoptarray.fail.com/') return 'setoptarray fail';

        return '';
    }

    public function getLastErrorNo() {
        if ($this->_data['url'] == 'http://exec.fail.com/')        return 1001;
        if ($this->_data['url'] == 'http://getinfo.fail.com/')     return 1002;
        if ($this->_data['url'] == 'http://setopt.fail.com/')      return 1003;
        if ($this->_data['url'] == 'http://setoptarray.fail.com/') return 1004;

        return 0;
    }

    public function close() {
    }

}
