<?php

/**

$ipv = new Norse\IPViking();
    Options:
        $ipv->setProxy(IPViking::PROXY_SANDBOX);
        $ipv->setApiKey(IPViking::SANDBOX_API_KEY);
        $ipv->setFormat(IPViking::FORMAT_JSON);
        $ipv->setRequestMethod(HTTP_GET);
        $piv->setReportLevel(IPViking::REPORT_NONE);
$ipq = $ipv->ipq($ip);

$ipq->response_header;
$ipq->response_body;
$ipq->response_code;
$ipq->risk_factor;
$ipq->entries;

 */

namespace Norse;

class IPViking {

    /* API Proxies */
    const PROXY_NORTHAMERICA = 'http://us.api.ipviking.com/api/';
    const PROXY_EUROPE       = 'http://eu.api.ipviking.com/api/';
    const PROXY_ASIAPACIFIC  = 'http://as.api.ipviking.com/api/';
    const PROXY_SOUTHAMERICA = 'http://la.api.ipviking.com/api/';
    const PROXY_SANDBOX      = 'http://beta.ipviking.com/api/';

    /* Data Formats */
    const FORMAT_JSON = 'json';
    const FORMAT_XML  = 'xml';

    /* Key for Sandbox Proxy */
    const SANDBOX_API_KEY = '8292777557e8eb8bc169c2af29e87ac07d0f1ac4857048044402dbee06ba5cea';

    /* For error reporting, record messages with priority of the given level or lower */
    const REPORT_NONE    = 0;
    const REPORT_ERROR   = 1;
    const REPORT_WARNING = 2;
    const REPORT_NOTICE  = 4;
    const REPORT_DEBUG   = 8;

    /* One of the API proxy constants defined above */
    protected $_proxy;

    /* One of the data format constants defined above */
    protected $_format;

    /* One of PHP's pre-defined HTTP_METH_* constants */
    protected $_request_method;

    /**
     * @param string $_api_key
     */
    protected $_api_key;

    /* One of the report level constants defined above */
    protected $_report_level;

    /**
     * A collection object for holding messages
     *
     * @params Reporting $_reporting
     */
    protected $_reporting;

    /**
     * Instantiate and configure the IPViking object given.  The argument may be either
     * an array of values, a string representing the path to a configuration file, or
     * null.
     *
     * @param array $config Accepted values include
     *      'proxy'
     *      'format'
     *      'request_method'
     *      'api_key'
     *
     */
    public function __construct($config = null) {
        $this->_reporting = new Reporting();

        if (is_array($config)) {
            $this->_loadConfigFromArray($config);
        } elseif (is_string($config)) {
            $this->_loadConfigFromFile($config);
        } elseif (is_null($config)) {
            $this->_loadConfigDefaults();
        } else {
            // WARK throw an exception -- unexpected value
        }
    }

    protected function _loadConfigDefaults() {
        $this->setProxy(self::PROXY_SANDBOX);
        $this->setFormat(self::FORMAT_JSON);
        $this->setRequestMethod(HTTP_GET);
        $this->setApiKey(self::SANDBOX_API_KEY);
        $this->setReportLevel(self::REPORT_NONE);
        $this->setReporting(new Reporting());
    }

    protected function _loadConfigFromArray($config) {
        // Set defaults for any values which may be missing
        $this->_loadConfigDefaults();

        // default proxy is sandbox
        if (!empty($config['proxy'])) {
            $predefined_proxies = $this->getPredefinedProxies();
            if (isset($predefined_proxies[strtolower($config['proxy'])])) {
                $this->setProxy($predefined_proxies[$config['proxy']]);
            } elseif (in_array($config['proxy'], $predefined_proxies)) {
                $this->setProxy($config['proxy']);
            } elseif ($this->_seemsUrlish($config['proxy'])) {
                $this->setProxy($config['proxy']);
            } else {
                // WARK issue warning, not setting proxy to value given
            }
        }

        // default format is json
        if (!empty($config['format'])) {
            $available_formats = $this->getAvailableFormats();
            if (isset($available_formats[strtolower($config['format'])])) {
                $this->setFormat($available_formats[$config['format']]);
            } elseif (in_array($config['format'], $available_formats)) {
                $this->setFormat($config['format']);
            } else {
                // WARK issue warning, invalid format specified
            }
        }

        // default request method is GET
        if (!empty($config['request_method'])) {
            $supported_request_methods = $this->getSupportedRequestMethods();
            if (isset($supported_request_methods[strtolower($config['request_method'])])) {
                $this->setRequestMethod($supported_request_methods[$config['request_method']]);
            } elseif (in_array($config['request_method'], $supported_request_methods)) {
                $this->setRequestMethod($config['request_method']);
            } else {
                // WARK issue warning, unsupported or invalid request method
            }
        }

        // default api key available for sandbox proxy only
        if (!empty($config['api_key'])) {
            $this->setApiKey($config['api_key']);
        } elseif ($this->getProxy() == self::PROXY_SANDBOX) {
            $this->setApiKey(self::SANDBOX_API_KEY);
        } else {
            // WARK issue error, cannot continue without an API Key
        }

        // default reporting level is NONE
        if (!empty($config['report_level'])) {
            $report_levels = $this->getReportLevels();
            if (isset($report_levels[strtolower($config['report_level']))) {
                $this->setReportLevel($report_levels[strtolower($config['report_level']));
            } elseif (in_array($config['report_level'])) {
                $this->setReportLevel($config['report_level']);
            } else {
                // WARK issue error, if report_level is there, it should mean something
            }
        }

    }

    protected function _loadConfigFromFile($file) {
        // File exists && is readable
            // expeted errors otherwise

        // if ini, parse_ini

        // if .php, include

        // if .csv

        // else -- wtf, error
    }

    public function getPredefinedProxies() {
        return array(
            'northamerica' => self::PROXY_NORTHAMERICA,
            'europe'       => self::PROXY_EUROPE,
            'asiapacific'  => self::PROXY_ASIAPACIFIC,
            'southamerica' => self::PROXY_SOUTHAMERICA,
            'sandbox'      => self::PROXY_SANDBOX,
        );
    }

    public function getAvailableFormats() {
        return array(
            'json' => self::FORMAT_JSON,
            'xml'  => self::FORMAT_XML,
        );
    }

    public function getSupportedRequestMethods() {
        return array(
            'get'    => HTTP_METH_GET,
            'post'   => HTTP_METH_POST,
            'put'    => HTTP_METH_PUT,
            'delete' => HTTP_METH_DELETE,
        );
    }

    public function getReportLevels() {
        return array(
            'none'    => REPORT_NONE,
            'error'   => REPORT_ERROR,
            'warning' => REPORT_WARNING,
            'notice'  => REPORT_NOTICE,
            'debug'   => REPORT_DEBUG,
        );
    }

    public function getProxy() {
        return $this->_proxy;
    }

    public function setProxy($proxy) {
        $this->_proxy = $proxy;
    }

    public function getFormat() {
        return $this->_format;
    }

    public function setFormat($format) {
        return $this->_format = $format;
    }

    public function getRequestMethod() {
        return $this->_request_method;
    }

    public function getRequestMethodString() {
        switch ($this->getRequestMethod()) {
            case HTTP_METH_GET:
                return 'GET';
            case HTTP_METH_POST:
                return 'POST';
            case HTTP_METH_PUT:
                return 'PUT';
            case HTTP_METH_DELETE:
                return 'DELETE';
            default:
                // WARK throw an exception, invalid request method
        }
    }

    public function setRequestMethod($method) {
        $this->_request_method = $request_method;
    }

    public function getApiKey() {
        return $this->_api_key;
    }

    public function setApiKey($api_key) {
        $this->_api_key = $api_key;
    }

    public function getReportLevel() {
        return $this->_report_level;
    }

    public function setReportLevel($level) {
        $this->_report_level = $level;
    }

    public function ipq($ip) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL            => $this->getProxy(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_CUSTOMREQUEST  => $this->getRequestMethodString(),
            CURLOPT_POSTFIELDS     => ($this->getRequestMethod() === HTTP_METH_POST) ? $this->getPostBody() : 'null',
            CURLOPT_HTTPHEADER     => array(
                'Content-Type: application/x-www-form-encoded'.
                'Accept: ' . $this->getFormatAccept(),
            )
        ));
        // USE CASE 1:
        //      User wants to pass an IP and get a risk_factor:   (float) $risk = IPV->getRiskFactorFor($ip {api_key, proxy/url});
        //      User wants full response object: $Risk = IPV->ipq($ip);
        //      User wants raw XML data:  $xmlData = IPV->getRawResult(ipq, xml, {api_key, proxy/url});
        $response = curl_exec($ch);
        $info     = curl_getinfo($ch);
        return new IPQResponse($response, $info);
    }

    public function risk($ip) {
        return new RiskResponse();
    }

    public function submit($ip) {
        return new SubmitResponse();
    }

}

class Category {
    $_category_id;
    $_category_name; // Botnet, Proxy

    $category = array(
         1 => 'Explicit Content',
         2 => 'Bogon Unadv',
         3 => 'Bogon Unass',
         4 => 'Proxy',
         5 => 'Botnet',
         6 => 'Financial',
         7 => 'Cyber Terrorism',
         8 => 'Identity',
         9 => 'Brute Force',
        10 => 'Cyber Stalking',
        11 => 'Arms',
        12 => 'Drugs',
        13 => 'Espionage',
        14 => 'Music Piracy',
        15 => 'Games Piracy',
        16 => 'Movie Piracy',
        17 => 'Publishing Piracy',
        18 => 'Stock Market',
        19 => 'Hacked',
        20 => 'Information Piracy',
        21 => 'High Risk',
        22 => 'HTTP',
        31 => 'Malicious Site',
        41 => 'Friendly Scanning',
        51 => 'Web Attacks',
        61 => 'DATA Harvesting',
        71 => 'Global Whitelist',
    );
}

class Protocol {
    $_protocol_id;
    $_protocal_name;
    $_overall_protocol;

    $protocol = array(
          7 => 'eDonkey User',
         17 => 'Ares User',
         19 => 'Gnutella User',
         30 => 'Unassigned IP',
         31 => 'Unadvertised IP',
         32 => 'Socket Proxy - Tor Exit',
         33 => 'IP Proxy',
         34 => 'Web Proxy',
         40 => 'Botnet CC',
         41 => 'Botnet Bot',
         50 => 'Carding User',
         51 => 'Carding Generator',
         52 => 'Financial Fraudster',
         53 => 'Financial Piracy - Publishing User',
         54 => 'Financial Insider - Information Leak',
         55 => 'Merchants API - Merchant-submitted Fraud',
         60 => 'Intelligence Collector',
         70 => 'Identity Theft - Phishing',
         80 => 'Cracking - Bruteforce Attacker',
         90 => 'Cyber Stalker',
        100 => 'Arms Dealer',
        110 => 'Narcotics - Mule Recruitment',
        111 => 'Narcotics - Narcotics Soliciting',
        112 => 'Pharmaceutical - Soliciting',
        120 => 'Industrial Espionage Server',
        130 => 'Hacked Computer',
        140 => 'Stock Scams - Pump and Dump',
        141 => 'Stock Scams = Short Scalping',
        150 => 'High Risk User TMI',
        151 => 'High Risk Network',
        152 => 'High Risk Site',
        153 => 'Porn',
        156 => 'Gambling',
        157 => 'Chat',
        158 => 'Webradio',
        159 => 'Webmail',
        160 => 'Warez',
        161 => 'Shopping',
        162 => 'Advertisement',
        163 => 'Movies',
        164 => 'Violence',
        165 => 'Music',
        166 => 'Hacking',
        167 => 'ISP',
        168 => 'Drugs',
        169 => 'Aggressive',
        170 => 'News',
        171 => 'Redirector',
        172 => 'Spyware',
        173 => 'Dating',
        174 => 'Dynamic',
        175 => 'Job Search',
        176 => 'Tracker',
        177 => 'Models',
        178 => 'Forum',
        179 => 'Web TV',
        180 => 'Downloads',
        181 => 'Ringtones',
        182 => 'Search Engine',
        183 => 'Social Net',
        184 => 'Update Sites',
        185 => 'Weapons',
        186 => 'Webphone',
        187 => 'Religion',
        188 => 'Image Hosting',
        189 => 'Podcast',
        190 => 'Hospitals',
        191 => 'Military',
        192 => 'Politics',
        193 => 'Remote Control',
        194 => 'Fortune Telling',
        195 => 'Library',
        196 => 'Cost Traps',
        197 => 'Homestyle',
        198 => 'Government',
        199 => 'Alcohol',
        200 => 'Radio TV',
        201 => 'Zeus',
        211 => 'Butterfly',
        221 => 'Keylogger Bot',
        231 => 'ZeroAccess Bot',
        241 => 'Palevo Bot',
        251 => 'ICE XX Bot',
        261 => 'SpyEye Bot',
        271 => 'Drive By Malware',
        281 => 'Binaries',
        291 => 'DDOS Scatter',
        301 => 'Port Scan',
        311 => 'SPAM Scatter',
        321 => 'Iframe Hidden',
        331 => 'IFrame Injected',
        341 => '.htaccess Redirect',
        351 => 'Bad Javascript',
        361 => 'Phising Site',
        371 => 'Friendly Port Scan',
        381 => 'Manual Whitelist Entry',
        391 => 'VPN AnchorFree.com',
    );
}

class IPQResponse {
    /**
{
  "ipviking": {
    "response": {
      "method": "ipq",
      "ip": "136.246.246.255",
      "host": "n/a",
      "clientID": "0",
      "transID": "0",
      "customID": "0",
      "risk_factor": "81.3",
      "risk_color": "orange",
      "risk_name": "High",
      "risk_desc": "High risk Involved",
      "timestamp": "2012-03-08T01:40:07-05:00",
      "factor_entries": "13",
      "ip_info": {
        "autonomous_system_number": "n/a",
        "autonomous_system_name": "n/a"
      },
      "geoloc": {
        "country": "United States",
        "country_code": "US",
        "region": "ILLINOIS",
        "region_code": "IL",
        "city": "Orland Park",
        "latitude": "41.6163",
        "longtitude": "-87.8371",
        "internet_service_provider": "Andrew Global Messaging Services",
        "organization": "Andrew Global Messaging Services"
      },
      "entries": {
        "reason": {
          "category_name": "Bogon Unadv",
          "category_id": "2",
          "category_factor": "35",
          "protocol_id": "31",
          "last_active": "2012-03-02T04:30:40-05:00",
          "overall_protocol": "Unadvertised IP",
          "protocol_name": "IP unadvertised"
        }
      },
      "factoring": {
        "reason": {
          "country_risk_factor": "4.1",
          "region_risk_factor": "1.2",
          "ip_resolve_factor": "-2",
          "asn_record_factor": "10",
          "asn_threat_factor": "5",
          "bgp_delegation_factor": "20",
          "iana_allocation_factor": "-1",
          "ipviking_personal_factor": "-1",
          "ipviking_category_factor": "35",
          "ipviking_geofilter_factor": "0",
          "ipviking_geofilter_rule": "0",
          "data_age_factor": "10",
          "geomatch_distance": "0",
          "geomatch_factor": "0",
          "search_volume_factor": "0"
        }
      }
    }
  }
}
*/

    $_http_code;
    $_response_header;
    $_response_body;
    $_response_code;
    $_risk_factor;
    $_entries; // Array of Category
    // WARK Fill out data and methods
}

class IPInfo {
    $_autonomous_system_number;
    $_autonomous_system_name;
}

class Geoloc {
    $_country;
    $_country_code;
    $_region;
    $_region_code;
    $_city;
    $_latitude;
    $_longitude;
    $_internet_service_provider;
    $_organization;
}

class Reason {
    $_country_risk_factor;
    $_region_risk_factor;
    $_ip_resolve_factor;
    $_asn_record_factor;
    $_asn_thread_factor;
    $_bgp_delegation_factor;
    $_iana_allocation_factor;
    $_ipviking_personal_factor;
    $_ipviking_category_factor;
    $_ipviking_geofilter_factor;
    $_ipviking_geofilter_rule;
    $_data_age_factor;
    $_search_volume_factor;
}

class RiskResponse {
    $_ip;
    $_host;
    $_client_id;
    $_transaction_id;
    $_custom_id;
    $_risk_factor;
    $_risk_color;
    $_risk_name;
    $_risk_desc;
    $_timestamp;
    $_factor_entries;
    $_ip_info; // IPInfo
    $_geoloc; // Geoloc
    $_details;
        $_reason; // Reason
}

class SubmitResponse {
}

class Reporting {
    /**
     * Add a message to the list
     *
     * @param $e Exception
     */
    public function addMessage(Exception $e) {
        $this->_messages[] = $e;
    }

    /**
     * Get every message in the list
     *
     * @returns array
     */
    public function getMessages() {
        return $this->_messages;
    }

    /**
     * Get the last message in the list
     *
     * @returns Exception
     */
    public function getLastMessage() {
        // WARK< this should return the last element, not remove it
        return array_pop($this->_messages);
    }

    /**
     * These methods get messages by report level
     */
    public function getErrors() {
        return $this->_getMessagesByLevel(IPViking::REPORT_ERROR);
    }

    public function getWarnings() {
        return $this->_getMessagesByLevel(IPViking::REPORT_WARNING);
    }

    public function getNotices() {
        return $this->_getMessagesByLevel(IPViking::REPORT_NOTICE);
    }

    public function getDebugs() {
        return $this->_getMessagesByLevel(IPViking::REPORT_DEBUG);
    }

    protected function _getMessagesByLevel($level) {
        $messages = array();

        foreach ($this->getMessages() as $message) {
            // WARK< What is the value of an exception that corresponds to 'level' here?
            if ($message->WARK === $level) {
                $messages[] = $level;
            }
        }

        return $messages;
    }

}
