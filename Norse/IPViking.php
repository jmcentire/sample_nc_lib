<?php

namespace Norse;

class IPViking {

    /* API Proxies */
    const PROXY_UNIVERSAL    = 'http://api.ipviking.com/api/';
    const PROXY_NORTHAMERICA = 'http://us.api.ipviking.com/api/';
    const PROXY_EUROPE       = 'http://eu.api.ipviking.com/api/';
    const PROXY_ASIAPACIFIC  = 'http://as.api.ipviking.com/api/';
    const PROXY_SOUTHAMERICA = 'http://la.api.ipviking.com/api/';
    const PROXY_SANDBOX      = 'http://beta.ipviking.com/api/';

    /* Key for Sandbox Proxy */
    const SANDBOX_API_KEY = '8292777557e8eb8bc169c2af29e87ac07d0f1ac4857048044402dbee06ba5cea';

    /* One of the API proxy constants defined above */
    protected $_proxy;

    /**
     * @param string $_api_key
     */
    protected $_api_key;

    /**
     * Instantiate and configure the IPViking object given.  The argument may be either
     * an array of values, a string representing the path to a configuration file, or
     * null.
     *
     * @param array $config Accepted values include
     *      'proxy'
     *      'api_key'
     *
     */
    public function __construct($config = null) {
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
        $this->setApiKey(self::SANDBOX_API_KEY);
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
            } elseif ($proxy = $this->_processUrl($config['proxy'])) {
                $this->setProxy($proxy);
            } else {
                // WARK issue warning, not setting proxy to value given
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

    }

    protected function _loadConfigFromFile($file) {
        if (!file_exists($file)) {
            // WARK issue error, file not found
        }

        if (!is_readable($file)) {
            // WARK issue error, cannot read file
        }

        if (!is_file($file)) {
            // WARK issue error, given directory, not a file
        }

        $config = parse_ini_file($file);

        if (!is_array($config)) {
            // WARK issue error, invalid .ini file
        }

        $this->_loadConfigFromArray($config);
    }

    protected function _processUrl($str) {
        // if parse_url can't handle it, it's probably not a valid url
        if (!$url = parse_url($str)) {
            return false;
        }

        // ensure that we have at least a host value
        if (!isset($url['host'])) {
            return false;
        }

        return (
            ((isset($url['scheme']))                        ? $url['scheme'] : 'http' ) .
                                            '://' .
            ((isset($url['user']))                          ? $url['user']   :     '' ) .
            ((isset($url['user'], $url['pass']))            ? ':'            :     '' ) .
            ((isset($url['pass']))                          ? $url['pass']   :     '' ) .
            ((isset($url['user']) || isset($url['pass']))   ? '@'            :     '' ) .
                                         $url['host'] .
            ((isset($url['path']))                          ? $url['path']   :     '' )
        );
    }

    public function getPredefinedProxies() {
        return array(
            'universal'    => self::PROXY_UNIVERSAL,
            'northamerica' => self::PROXY_NORTHAMERICA,
            'europe'       => self::PROXY_EUROPE,
            'asiapacific'  => self::PROXY_ASIAPACIFIC,
            'southamerica' => self::PROXY_SOUTHAMERICA,
            'sandbox'      => self::PROXY_SANDBOX,
        );
    }

    public function getProxy() {
        return $this->_proxy;
    }

    public function setProxy($proxy) {
        $this->_proxy = $proxy;
    }

    public function getApiKey() {
        return $this->_api_key;
    }

    public function setApiKey($api_key) {
        $this->_api_key = $api_key;
    }

    public function getConfig() {
        return array(
            'proxy'   => $this->getProxy(),
            'api_key' => $this->getApiKey(),
        );
    }

    public function ipq($ip) {
        $ipq = new IPViking\IPQ_Request($this->getConfig(), $ip);
        return $ipq->exec();
    }

    public function getIPQRequest($ip) {
        return new IPViking\IPQ_Request($this->getConfig(), $ip);
    }

    public function xml($ip) {
        $ipq = new IPViking\IPQ_Request($this->getConfig(), $ip);
        $ipq->setFormat('xml');
        return $ipq->execXML();
    }

    public function submission($ip, $protocol, $category) {
    }

    public function getGeoFilterSettings() {
    }

    public function addGeoFilter($clientID, $action, $category, $location) {
       $location = array($country, $region, $city, $zip);
    }

    // are all of these needed to delete?
    public function deleteGeoFilter($clientID, $action, $cateogry, $location) {
    }

    public function getRiskFactorSettings() {
    }

    public function addRiskFactor($risk_id, $risk_good_value, $risk_bad_value) {
        // good 0 < 100
        // bad -1 > -100
    }

    public function deleteRiskFactor($risk_id, $risk_good_value, $risk_bad_value) {
        // good 0 < 100
        // bad -1 > -100
    }

}
