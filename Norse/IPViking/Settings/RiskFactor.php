<?php

namespace Norse\IPViking;

class Settings_RiskFactor extends Request {
    protected $_collection;

    public function __construct($config) {
        parent::__construct($config);
    }

    public function setCollection(Settings_RiskFactor_Collection $collection) {
        $this->_collection = $collection;
    }

    public function getCollection() {
        return $this->_collection;
    }

    protected function _getRiskFactorXML() {
        $collection = $this->getCollection();

        if (empty($collection)) return null;
        return $this->getCollection()->getRiskFactorXML();
    }

    protected function _getBodyFields() {
        $body_fields = parent::_getBodyFields();

        $body_fields['method']      = 'riskfactor';
        $body_fields['settingsxml'] = $this->_getRiskFactorXML();

        return $body_fields;
    }

    protected function _getCurlOpts() {
        $curl_opts = parent::_getCurlOpts();

        $curl_opts[CURLOPT_POST]       = true;
        $curl_opts[CURLOPT_POSTFIELDS] = $this->_getEncodedBody();
        $curl_opts[CURLOPT_HTTPHEADER] = $this->_getHttpHeader();

        return $curl_opts;
    }

    public function process() {
        $this->_setCurlOpts();

        $curl_response = parent::_curlExec();
        $curl_info     = parent::_curlInfo();

        return new Settings_RiskFactor_Collection($curl_response, $curl_info);
    }

    public function getCurrentSettings() {
        return $this->process();
    }

    public function addRiskFactor(Settings_RiskFactor_Factor $filter) {
        $filter->setCommand('add');
        $this->setCollection(new Settings_RiskFactor_Collection(array($filter)));

        return $this->process();
    }

    public function deleteRiskFactor(Settings_RiskFactor_Factor $filter) {
        $filter->setCommand('delete');
        $this->setCollection(new Settings_RiskFactor_Collection(array($filter)));

        return $this->process();
    }

    public function updateRiskFactors(array $riskfactors) {
        foreach ($riskfactors as &$factor) {
            if (!$factor instanceof Settings_RiskFactor_Factor) {
                $factor = new Settings_RiskFactor_Factor($factor);
            }

            if (empty($factor->command)) {
                throw new Exception_InvalidRiskFactor('Instance of Settings_RiskFactor_Factor requires valid command value.', 182591);
            }
        }

        $this->setCollection(new Settings_RiskFactor_Collection($riskfactors));

        return $this->process();
    }

}
