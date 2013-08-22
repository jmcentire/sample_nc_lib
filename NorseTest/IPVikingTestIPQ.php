<?php

require_once __DIR__ . '/includes.php';

class IPVikingTestIPQ extends PHPUnit_Framework_TestCase {
    const VALID_IP   = '67.13.46.123';
    const INVALID_IP = '1234';

    /**
     * Instance of Norse\IPViking using NorseTest\Curl set in setUp()
     */
    protected $_ipv;

    /**
     * Set up Norse\IPViking with defaults except for curl_class of NorseTest\Curl
     */
    public function setUp() {
        $this->_ipv = new Norse\IPViking(array(
            'curl_class' => 'NorseTest\Curl',
        ));
    }

    /**
     * When overriding the default curl class, the object must be a valid instance of the
     * Norse\IPViking\curl_interface.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRequest
     * @expectedExceptionMessage The IP provided is not a valid IP address.
     * @expectedExceptionCode    182530
     */
    public function testIPQInvalidIP() {
        $ipq = $this->_ipv->ipq(self::INVALID_IP);
    }

    /**
     * When overriding the default curl class, the object must be a valid instance of the
     * Norse\IPViking\curl_interface.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRequest
     * @expectedExceptionMessage The IP provided is not a valid IP address.
     * @expectedExceptionCode    182531
     */
    public function testGetIPQRequestInvalidIP() {
        $ipq = $this->_ipv->getIPQRequest(self::INVALID_IP);
    }

    /**
     * When overriding the default curl class, the object must be a valid instance of the
     * Norse\IPViking\curl_interface.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRequest
     * @expectedExceptionMessage The IP provided is not a valid IP address.
     * @expectedExceptionCode    182532
     */
    public function testXMLInvalidIP() {
        $ipq = $this->_ipv->xml(self::INVALID_IP);
    }

    /**
     * Ensure that an invalid JSON response results in an error.
     *
     * @expectedException        Norse\IPViking\Exception_Json
     * @expectedExceptionMessage Error decoding json response:
     * @expectedExceptionCode    4
     */
    public function testInvalidJSONResponse() {
        $this->_ipv = new Norse\IPViking(array(
            'proxy'      => 'http://json.fail.com/',
            'api_key'    => '1234',
            'curl_class' => 'NorseTest\Curl',
        ));
        $ipq = $this->_ipv->ipq(self::VALID_IP);
    }

}
