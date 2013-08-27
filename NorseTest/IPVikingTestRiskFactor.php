<?php

require_once __DIR__ . '/includes.php';

class IPVikingTestRiskFactor extends PHPUnit_Framework_TestCase {

    /**
     * Instance of Norse\IPViking using NorseTest\Curl set in setUp()
     */
    protected $_ipv;

    /**
     * Set up Norse\IPViking with defaults except for curl_class of NorseTest\Curl
     */
    public function setUp() {
        $this->_ipv = new Norse\IPViking(array(
            'proxy'      => 'http://riskfactor.test.com/',
            'api_key'    => '1234',
            'curl_class' => 'NorseTest\Curl',
        ));
    }

    /**
     * Given a sample response, we verify the collection object is populated as expected.
     */
    protected function _validateResponse($collection) {
    }

    /**
     * The following methods verfiy a few different factors.
     */
    protected function _validateFactor423($factor) {
        $this->assertEquals(423, $factor->getFactorID());
        $this->assertEquals('allow', $factor->getAction());
        $this->assertEquals(0, $factor->getClientID());
        $this->assertEquals('country', $factor->getCategory());
        $this->assertEquals('US', $factor->getCountry());
        $this->assertEquals('-', $factor->getRegion());
        $this->assertEquals('-', $factor->getCity());
        $this->assertEquals('-', $factor->getZip());
        $this->assertEquals(4140, $factor->getHits());
    }

    protected function _validateFactor433($factor) {
        $this->assertEquals(433, $factor->getFactorID());
        $this->assertEquals('deny', $factor->getAction());
        $this->assertEquals(0, $factor->getClientID());
        $this->assertEquals('master', $factor->getCategory());
        $this->assertEquals('-', $factor->getCountry());
        $this->assertEquals('-', $factor->getRegion());
        $this->assertEquals('-', $factor->getCity());
        $this->assertEquals('-', $factor->getZip());
        $this->assertEquals(0, $factor->getHits());
    }

    protected function _validateFactor443($factor) {
        $this->assertEquals(443, $factor->getFactorID());
        $this->assertEquals('allow', $factor->getAction());
        $this->assertEquals(0, $factor->getClientID());
        $this->assertEquals('city', $factor->getCategory());
        $this->assertEquals('TW', $factor->getCountry());
        $this->assertEquals('04', $factor->getRegion());
        $this->assertEquals('PONG', $factor->getCity());
        $this->assertEquals('-', $factor->getZip());
        $this->assertEquals(0, $factor->getHits());
    }

    protected function _validateFactor1031($factor) {
        $this->assertEquals(1031, $factor->getFactorID());
        $this->assertEquals('allow', $factor->getAction());
        $this->assertEquals(0, $factor->getClientID());
        $this->assertEquals('master', $factor->getCategory());
        $this->assertEquals('-', $factor->getCountry());
        $this->assertEquals('-', $factor->getRegion());
        $this->assertEquals('-', $factor->getCity());
        $this->assertEquals('-', $factor->getZip());
        $this->assertEquals(0, $factor->getHits());
    }

    /**
     * Verify that the code produces the expected object given a contrived data source
     * from the test curl object.
     */
    public function testGetRiskFactorSettingsSuccess() {
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Collection', $riskfactors = $this->_ipv->getRiskFactorSettings());
        $this->_validateResponse($riskfactors);
    }

    /**
     * Verify that the code returns an instance of Settings_RiskFactor_Factor.
     */
    public function testGetNewRiskFactor() {
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Factor', $this->_ipv->getNewRiskFactor());
    }

    /**
     * Verify the expected exception is thrown when the factor's 'command' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRiskFactor
     * @expectedExceptionMessage Unexpected format for instantiation of Norse\IPViking\Settings_RiskFactor_Factor object.
     * @expectedExceptionCode    182590
     */
    public function testNewRiskFactorFactorFromString() {
        $riskfactor = new Norse\IPViking\Settings_RiskFactor_Factor('foo');
    }

    /**
     * Verify the expected exception is thrown when the factor's construct value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRiskFactor
     * @expectedExceptionMessage Unknown format for first argument of Settings_RiskFactor_Collection constructor.
     * @expectedExceptionCode    182592
     */
    public function testNewRiskFactorCollectionFromInt() {
        $geofilter = new Norse\IPViking\Settings_RiskFactor_Collection(123);
    }

    /**
     * Verify there are no errors when adding a RiskFactor.
     */
    public function testAddRiskFactor() {
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Factor',     $factor = $this->_ipv->getNewRiskFactor());
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Collection', $collection = $this->_ipv->addRiskFactor($factor));
    }

    /**
     * Verify there are no errors when deleting a RiskFactor.
     */
    public function testDeleteRiskFactor() {
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Factor',     $factor = $this->_ipv->getNewRiskFactor());
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Collection', $collection = $this->_ipv->deleteRiskFactor($factor));
    }

    /**
     * Verify there are no errors when updating a RiskFactor with command 'add'.
     */
    public function testUpdateRiskFactorsCommandAdd() {
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Factor',     $factor = $this->_ipv->getNewRiskFactor());
        $factor->setCommand('add');
        $factors = array($factor);

        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Collection', $collection = $this->_ipv->updateRiskFactors($factors));
    }

    /**
     * Verify there are no errors when updating a RiskFactor with command 'delete'.
     */
    public function testUpdateRiskFactorsCommandDelete() {
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Factor',     $factor = $this->_ipv->getNewRiskFactor());
        $factor->setCommand('delete');
        $factors = array($factor);

        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Collection', $collection = $this->_ipv->updateRiskFactors($factors));
    }

    /**
     * Verify the expected exception is thrown when the factor's 'command' value is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRiskFactor
     * @expectedExceptionMessage Instance of Settings_RiskFactor_Factor requires valid command value.
     * @expectedExceptionCode    182591
     */
    public function testUpdateRiskFactorsNoCommand() {
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Factor',     $factor = $this->_ipv->getNewRiskFactor());
        $factors = array($factor);

        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Collection', $collection = $this->_ipv->updateRiskFactors($factors));
    }

    /**
     * Verify the expected exception is thrown when the factor's 'command' value is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRiskFactor
     * @expectedExceptionMessage Invalid value for Settings_RiskFactor_Factor::command;
     * @expectedExceptionCode    182599
     */
    public function testUpdateRiskFactorsMissingCommand() {
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Factor',     $factor = $this->_ipv->getNewRiskFactor());
        $factor->setCommand(null);
        $factors = array($factor);

        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Collection', $collection = $this->_ipv->updateRiskFactors($factors));
    }

    /**
     * Verify the expected exception is thrown when the factor's 'command' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRiskFactor
     * @expectedExceptionMessage Invalid value for Settings_RiskFactor_Factor::command;
     * @expectedExceptionCode    182599
     */
    public function testUpdateRiskFactorsInvalidCommand() {
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Factor',     $factor = $this->_ipv->getNewRiskFactor());
        $factor->setCommand('foo');
        $factors = array($factor);

        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Collection', $collection = $this->_ipv->updateRiskFactors($factors));
    }

    /**
     * Verify that we can set the factor's 'risk_good_value' value.
     */
    public function testUpdateRiskFactorsValidRiskGoodValue() {
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Factor',     $factor = $this->_ipv->getNewRiskFactor());
        $factor->setCommand('add');
        $factor->setRiskGoodValue(0);
        $factors = array($factor);

        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Collection', $collection = $this->_ipv->updateRiskFactors($factors));
    }

    /**
     * Verify the expected exception is thrown when the factor's 'risk_good_value' value is not numeric.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRiskFactor
     * @expectedExceptionMessage Settings_RiskFactor_Factor::risk_good_value must be an integer, given
     * @expectedExceptionCode    182593
     */
    public function testUpdateRiskFactorsInvalidRiskGoodValue() {
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Factor',     $factor = $this->_ipv->getNewRiskFactor());
        $factor->setCommand('add');
        $factor->setRiskGoodValue('foo');
        $factors = array($factor);

        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Collection', $collection = $this->_ipv->updateRiskFactors($factors));
    }

    /**
     * Verify that we can set the factor's 'risk_bad_value' value.
     */
    public function testUpdateRiskFactorsValidRiskBadValue() {
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Factor',     $factor = $this->_ipv->getNewRiskFactor());
        $factor->setCommand('add');
        $factor->setRiskBadValue(0);
        $factors = array($factor);

        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Collection', $collection = $this->_ipv->updateRiskFactors($factors));
    }

    /**
     * Verify the expected exception is thrown when the factor's 'risk_bad_value' value is not numeric.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRiskFactor
     * @expectedExceptionMessage Settings_RiskFactor_Factor::risk_bad_value must be an integer, given
     * @expectedExceptionCode    182596
     */
    public function testUpdateRiskFactorsInvalidRiskBadValue() {
        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Factor',     $factor = $this->_ipv->getNewRiskFactor());
        $factor->setCommand('add');
        $factor->setRiskBadValue('foo');
        $factors = array($factor);

        $this->assertInstanceOf('Norse\IPViking\Settings_RiskFactor_Collection', $collection = $this->_ipv->updateRiskFactors($factors));
    }

    /**
     * Verify the expected exception is thrown when the cURL response does not have a 'settings' element.
     *
     * @expectedException        Norse\IPViking\Exception_UnexpectedResponse
     * @expectedExceptionMessage Expecting element 'settings' in response:
     * @expectedExceptionCode    182543
     */
    public function testResponseNoRiskFactors() {
        $json = '{"response": "invalid"}';
        new Norse\IPViking\Settings_RiskFactor_Collection($json);
    }

    /**
     * Verify the expected exception is thrown when the cURL response 'settings' element is not formatted as expected.
     *
     * @expectedException        Norse\IPViking\Exception_UnexpectedResponse
     * @expectedExceptionMessage Unexpected data type for 'settings' in response:
     * @expectedExceptionCode    182544
     */
    public function testResponseRiskFactorSettingsNotValidType() {
        $json = '{"settings": "invalid"}';
        new Norse\IPViking\Settings_RiskFactor_Collection($json);
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
        $ipq = $this->_ipv->getRiskFactorSettings();
    }

}
