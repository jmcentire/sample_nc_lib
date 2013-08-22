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
     * @param string $_curl
     */
    protected $_curl;

    /**
     * Instantiate and configure the IPViking object given.  The argument may be either
     * an array of values, a string representing the path to a configuration file, or
     * null.
     *
     * @param array $config Accepted values include
     *      'proxy'
     *      'api_key'
     *
     * WARK @throws
     */
    public function __construct($config = null) {
        if (is_array($config)) {
            $this->_loadConfigFromArray($config);
        } elseif (is_string($config)) {
            $this->_loadConfigFromFile($config);
        } elseif (is_null($config)) {
            $this->_loadConfigDefaults();
        } else {
            throw new IPViking\Exception_InvalidConfig('Unable to determine format of provided configuration.', 182500);
        }
    }

    protected function _loadConfigDefaults() {
        $this->setProxy(self::PROXY_SANDBOX);
        $this->setApiKey(self::SANDBOX_API_KEY);
        $this->setCurl('Norse\IPViking\Curl');
    }

    /**
     * WARK @throws
     */
    protected function _loadConfigFromArray($config) {
        // Set defaults for any values which may be missing
        $this->_loadConfigDefaults();

        // default proxy is sandbox
        if (!empty($config['proxy'])) {
            $predefined_proxies = $this->getPredefinedProxies();
            if (is_string($config['proxy'])) {
                if (isset($predefined_proxies[strtolower($config['proxy'])])) {
                    $this->setProxy($predefined_proxies[strtolower($config['proxy'])]);
                } elseif (in_array($config['proxy'], $predefined_proxies)) {
                    $this->setProxy($config['proxy']);
                } else {
                    $this->setProxy($this->_processUrl($config['proxy']));
                }
            } else {
                throw new IPViking\Exception_InvalidConfig('Unable to process proxy designation, check documentation.', 182501);
            }
        }

        // default api key available for sandbox proxy only
        if (!empty($config['api_key'])) {
            $this->setApiKey($config['api_key']);
        } elseif ($this->getProxy() == self::PROXY_SANDBOX) {
            $this->setApiKey(self::SANDBOX_API_KEY);
        } else {
            throw new IPViking\Exception_InvalidConfig('Missing or invalid API key.  A valid API key must be provided for any proxy other than the sandbox.', 182502);
        }

        if (!empty($config['curl_class'])) {
            $this->setCurl($config['curl_class']);
        } else {
            $this->setCurl('Norse\IPViking\Curl');
        }

    }

    /**
     * WARK @throws
     */
    protected function _loadConfigFromFile($file) {
        if (!file_exists($file)) {
            throw new IPViking\Exception_InvalidConfig('Unable to locate config file, check path.', 182503);
        }

        if (!is_readable($file)) {
            throw new IPViking\Exception_InvalidConfig('Unable to read config file, check permissions.', 182504);
        }

        if (!is_file($file)) {
            throw new IPViking\Exception_InvalidConfig('Unable to locate config file, directory path given.', 182505);
        }

        $config = parse_ini_file($file);

        if (false === $config) {
            throw new IPViking\Exception_InvalidConfig('Unable to parse config file, ensure it is a valid .ini', 182506);
        }

        if (!is_array($config)) {
            throw new IPViking\Exception_InvalidConfig('Unable to parse config file, ensure it is a valid .ini', 182507);
        }

        $this->_loadConfigFromArray($config);
    }

    /**
     * WARK @throws
     */
    protected function _processUrl($str) {
        // if parse_url can't handle it, it's probably not a valid url
        if (!$url = parse_url($str)) {
            throw new IPViking\Exception_InvalidConfig('Proxy value provided is not a valid URL.', 182508);
        }

        // ensure that we have at least a host value
        if (!isset($url['host'])) {
            throw new IPViking\Exception_InvalidConfig('Cannot determine proxy host value, check URL.', 182509);
        }

        return (
            ((isset($url['scheme'])) ? $url['scheme'] : 'http' ) .
            '://' .
            ((isset($url['user'])) ? $url['user'] : '' ) .
            ((isset($url['user'], $url['pass'])) ? ':' : '' ) .
            ((isset($url['pass'])) ? $url['pass'] : '' ) .
            ((isset($url['user']) || isset($url['pass'])) ? '@' : '' ) .
            $url['host'] .
            ((isset($url['port'])) ? ':' . $url['port'] : '' ) .
            ((isset($url['path'])) ? $url['path'] : '' ) .
            ((isset($url['query'])) ? '?' . $url['query'] : '' ) .
            ((isset($url['fragment'])) ? '#' . $url['fragment'] : '' )
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

    public function setProxy($proxy) {
        $this->_proxy = $proxy;
    }

    public function getProxy() {
        return $this->_proxy;
    }

    public function setApiKey($api_key) {
        $this->_api_key = $api_key;
    }

    public function getApiKey() {
        return $this->_api_key;
    }

    /**
     * WARK @throws
     */
    public function setCurl($class) {
        $curl = new $class();

        if (!$curl instanceof IPViking\CurlInterface) {
            throw new IPViking\Exception_InvalidConfig('Curl class must implement Norse\IPViking\CurlInterface.', 182500);
        }

        $this->_curl = $curl;
    }

    public function getCurl() {
        return $this->_curl;
    }

    public function getConfig() {
        return array(
            'proxy'   => $this->getProxy(),
            'api_key' => $this->getApiKey(),
            'curl'    => $this->getCurl(),
        );
    }


    protected function _validateIP($ip) {
        return $this->_validateIPv4($ip) || $this->_validateIPv6($ip);
    }

    protected function _validateIPv4($ip) {
        return preg_match('/^(([01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}([01]?\d{1,2}|2[0-4]\d|25[0-5])/', $ip);
    }

    protected function _validateIPv6($ip) {
        return false;
    }


    /**
     * WARK @throws
     */
    public function ipq($ip) {
        if (!$this->_validateIP($ip)) {
            throw new IPViking\Exception_InvalidRequest('The IP provided is not a valid IP address.', 182530);
        }

        $ipq = new IPViking\IPQ_Request($this->getConfig(), $ip);
        return $ipq->process();
    }

    /**
     * WARK @throws
     */
    public function getIPQRequest($ip) {
        if (!$this->_validateIP($ip)) {
            throw new IPViking\Exception_InvalidRequest('The IP provided is not a valid IP address.', 182531);
        }
        return new IPViking\IPQ_Request($this->getConfig(), $ip);
    }

    /**
     * WARK @throws
     */
    public function xml($ip) {
        if (!$this->_validateIP($ip)) {
            throw new IPViking\Exception_InvalidRequest('The IP provided is not a valid IP address.', 182532);
        }

        $ipq = new IPViking\IPQ_Request($this->getConfig(), $ip);
        $ipq->setFormat('xml');
        return $ipq->exec();
    }


    /**
     * WARK @throws
     */
    public function submission($ip, $protocol, $category, $timestamp) {
        if (!$this->_validateIP($ip)) {
            throw new IPViking\Exception_InvalidRequest('The IP provided is not a valid IP address.', 182533);
        }

        $submission = new IPViking\Submission_Request($this->getConfig(), $ip, $protocol, $category, $timestamp);
        return $submission->process();
    }


    public function getGeoFilterSettings() {
        $geofilter_settings = new IPViking\Settings_GeoFilter($this->getConfig());
        return $geofilter_settings->getCurrentSettings();
    }

    public function getNewGeoFilter() {
        return new IPViking\Settings_GeoFilter_Filter();
    }

    public function addGeoFilter(IPViking\Settings_GeoFilter_Filter $filter) {
        $geofilter_settings = new IPViking\Settings_GeoFilter($this->getConfig());
        return $geofilter_settings->addGeoFilter($filter);
    }

    public function deleteGeoFilter(IPViking\Settings_GeoFilter_Filter $filter) {
        $geofilter_settings = new IPViking\Settings_GeoFilter($this->getConfig());
        return $geofilter_settings->deleteGeoFilter($filter);
    }

    /**
     * WARK @throws Exception
     */
    public function updateGeoFilters(array $filters) {
        $geofilter_settings = new IPViking\Settings_GeoFilter($this->getConfig());
        return $geofilter_settings->updateGeoFilters($filters);
    }


    public function getRiskFactorSettings() {
        $riskfactor_settings = new IPViking\Settings_RiskFactor($this->getConfig());
        return $riskfactor_settings->getCurrentSettings();
    }

    public function getNewRiskFactor() {
        return new IPViking\Settings_RiskFactor_Factor();
    }

    public function addRiskFactor(IPViking\Settings_RiskFactor_Factor $factor) {
        $riskfactor_settings = new IPViking\Settings_RiskFactor($this->getConfig());
        return $riskfactor_settings->addRiskFactor($factor);
    }

    public function deleteRiskFactor(IPViking\Settings_RiskFactor_Factor $factor) {
        $riskfactor_settings = new IPViking\Settings_RiskFactor($this->getConfig());
        return $riskfactor_settings->deleteRiskFactor($factor);
    }

    /**
     * WARK @throws Exception
     */
    public function updateRiskFactors(array $factors) {
        $riskfactor_settings = new IPViking\Settings_RiskFactor($this->getConfig());
        return $riskfactor_settings->updateRiskFactors($factors);
    }

}
