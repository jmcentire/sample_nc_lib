<?php
/**
 * An object representation of IPViking RiskFactor Settings data.
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

class Settings_RiskFactor_Factor {
    protected $_risk_id;
    protected $_command;
    protected $_risk_attribute;
    protected $_risk_good_value;
    protected $_risk_bad_value;

    /**
     * The constructor accepts either an object or an array of data.
     *
     * @param mixed $filter An object or array encapsulating RiskFactor data.
     *
     * @throws Exception_InvalidRiskFactor:182580 when the provided argument is neither an object nor an array.
     */
    public function __construct($factor = null) {
        if (!empty($factor)) {
            if (is_object($factor)) {
                $this->_processObject($factor);
            } elseif (is_array($factor)) {
                $this->_processArray($factor);
            } else {
                throw new Exception_InvalidRiskFactor('Unexpected format for instantiation of Norse\IPViking\Settings_RiskFactor_Factor object.', 182590);
            }
        }
    }

    protected function _processObject($factor) {
        if (isset($factor->risk_id))         $this->setRiskID($factor->risk_id);
        if (isset($factor->command))         $this->setCommand($factor->command);
        if (isset($factor->risk_attribute))  $this->setRiskAttribute($factor->risk_attribute);
        if (isset($factor->risk_good_value)) $this->setRiskGoodValue($factor->risk_good_value);
        if (isset($factor->risk_bad_value))  $this->setRiskBadValue($factor->risk_bad_value);
    }

    protected function _processArray($factor) {
        if (isset($factor['risk_id']))         $this->setRiskID($factor['risk_id']);
        if (isset($factor['command']))         $this->setCommand($factor['command']);
        if (isset($factor['risk_attribute']))  $this->setRiskAttribute($factor['risk_attribute']);
        if (isset($factor['risk_good_value'])) $this->setRiskGoodValue($factor['risk_good_value']);
        if (isset($factor['risk_bad_value']))  $this->setRiskBadValue($factor['risk_bad_value']);
    }


    /**
     * Basic accessor methods.
     */

    public function setRiskID($risk_id) {
        $this->_risk_id = $risk_id;
    }

    public function getRiskID() {
        return $this->_risk_id;
    }

    public function setCommand($command) {
        $command = strtolower($command);
        if ($command !== 'add' && $command !== 'delete') {
            throw new Exception_InvalidRiskFactor('Invalid value for Settings_RiskFactor_Factor::command; expecting \'add\' or \'delete\', given ' .  var_export($command, true), 182599);
        }

        $this->_command = $command;
    }

    public function getCommand() {
        return $this->_command;
    }

    public function setRiskAttribute($risk_attribute) {
        $this->_risk_attribute = $risk_attribute;
    }

    public function getRiskAttribute() {
        return $this->_risk_attribute;
    }

    public function setRiskGoodValue($risk_good_value) {
        if (!is_numeric($risk_good_value)) {
            throw new Exception_InvalidRiskFactor('Settings_RiskFactor_Factor::risk_good_value must be an integer, given ' . gettype($risk_good_value), 182593);
        }

        $this->_risk_good_value = $risk_good_value;
    }

    public function getRiskGoodValue() {
        return $this->_risk_good_value;
    }

    public function setRiskBadValue($risk_bad_value) {
        if (!is_numeric($risk_bad_value)) {
            throw new Exception_InvalidRiskFactor('Settings_RiskFactor_Factor::risk_bad_value must be an integer, given ' . gettype($risk_bad_value), 182596);
        }

        $this->_risk_bad_value = $risk_bad_value;
    }

    public function getRiskBadValue() {
        return $this->_risk_bad_value;
    }

}
