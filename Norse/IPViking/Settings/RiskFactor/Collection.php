<?php

namespace Norse\IPViking;

class Settings_RiskFactor_Collection extends Response {
    protected $_riskfactors;

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
                throw new Exception_InvalidRiskFactor('Unknown format for first argument.', 182592);
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

        $this->_processArray($decoded->settings);
    }

    protected function _processObject($factor) {
        $this->addRiskFactor(new Settings_RiskFactor_Factor($factor));
    }

    protected function _processArray(array $riskfactors) {
        if (empty($riskfactors)) return null;

        foreach ($riskfactors as $factor) {
            $this->addRiskFactor(new Settings_RiskFactor_Factor($factor));
        }
    }

    public function addRiskFactor(Settings_RiskFactor_Factor $factor) {
        $this->_riskfactors[] = $factor;
    }

    public function getRiskFactors() {
        return $this->_riskfactors;
    }

    public function getRiskFactorXML() {
        $riskfactors = $this->getRiskFactors();

        if (empty($riskfactors) || !is_array($riskfactors)) {
            return null;
        }

        $riskfactor_xml = <<<XML
<?xml version=1.0?>
<ipviking>
    <settings>
XML;

        foreach ($geofitlers as $factor) {
            $riskfactor_xml .= <<<XML
        <riskfactors>
            <risk_id>{$factor->getRiskID()}</risk_id>
            <command>{$factor->getCommand()}</command>
            <risk_good_value>{$factor->getRiskGoodValue()}</risk_good_value>
            <risk_bad_value>{$factor->getRiskBadValue()}</risk_bad_value>
        </riskfactors>
XML;
        }

        $riskfactor .= <<<XML
    </settings>
</ipviking>
XML;
        return $riskfactor_xml;
    }

}
