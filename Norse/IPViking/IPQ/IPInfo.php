<?php

namespace Norse\IPViking;

class IPQ_IPInfo {
	protected $_autonomous_system_number;
	protected $_autonomous_system_name;

    public function __construct($ip_info) {
        $this->setAutonomousSystemNumber($ip_info->autonomous_system_number);
        $this->setAutonomousSystemName($ip_info->autonomous_system_name);
    }

    public function setAutonomousSystemNumber($autonomous_system_number) {
        $this->_autonomous_system_number = $autonomous_system_number;
    }

    public function getAutonomousSystemNumber() {
        return $this->_autonomous_system_number;
    }

    public function setAutonomousSystemName($autonomous_system_name) {
        $this->_autonomous_system_name = $autonomous_system_name;
    }

    public function getAutonomousSystemName() {
        return $this->_autonomous_system_name;
    }

}
