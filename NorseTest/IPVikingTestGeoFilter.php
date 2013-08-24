<?php

require_once __DIR__ . '/includes.php';

class IPVikingTestGeoFilter extends PHPUnit_Framework_TestCase {

    /**
     * Instance of Norse\IPViking using NorseTest\Curl set in setUp()
     */
    protected $_ipv;

    /**
     * Set up Norse\IPViking with defaults except for curl_class of NorseTest\Curl
     */
    public function setUp() {
        $this->_ipv = new Norse\IPViking(array(
            'proxy'      => 'http://geofilter.test.com/',
            'api_key'    => '1234',
            'curl_class' => 'NorseTest\Curl',
        ));
    }

    protected function _validateResponse($geofilter) {
        // Currently GeoFilter doesn't return any data
    }

    /**
     * Verify that the code produces the expected object given a contrived data source
     * from the test curl object.
     */
    public function testGetGeoFilterSettingsSuccess() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $geofilter = $this->_ipv->getGeoFilterSettings());
        $this->_validateResponse($geofilter);
    }

    /**
     * Verify that the code returns an instance of Settings_GeoFilter_Filter.
     */
    public function testGetNewGeoFilter() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter', $geofilter = $this->_ipv->getNewGeoFilter());
    }

    /**
     * Verify the expected exception is thrown when the filter's 'command' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Unexpected format for instantiation of Norse\IPViking\Settings_GeoFilter_Filter object.
     * @expectedExceptionCode    182580
     */
    public function testNewGeoFilterFilterFromString() {
        $geofilter = new Norse\IPViking\Settings_GeoFilter_Filter('foo');
    }

    /**
     * Verify the expected exception is thrown when the filter's 'command' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Unknown format for first argument of Settings_GeoFilter_Collection constructor.
     * @expectedExceptionCode    182582
     */
    public function testNewGeoFilterCollectionFromInt() {
        $geofilter = new Norse\IPViking\Settings_GeoFilter_Collection(123);
    }

    /**
     * Verify there are no errors when adding a GeoFilter.
     */
    public function testAddGeoFilter() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->addGeoFilter($filter));
    }

    /**
     * Verify there are no errors when deleting a GeoFilter.
     */
    public function testDeleteGeoFilter() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->deleteGeoFilter($filter));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with command 'add'.
     */
    public function testUpdateGeoFiltersCommandAdd() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with command 'delete'.
     */
    public function testUpdateGeoFiltersCommandDelete() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('delete');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'command' value is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::command;
     * @expectedExceptionCode    182589
     */
    public function testUpdateGeoFiltersMissingCommand() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand(null);
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'command' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::command;
     * @expectedExceptionCode    182589
     */
    public function testUpdateGeoFiltersInvalidCommand() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('foo');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify that we can set the filter's 'client_id' value.
     */
    public function testUpdateGeoFiltersInvalidClientIDValid() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setClientID(0);
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'client_id' value is a string.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::client_id;
     * @expectedExceptionCode    182584
     */
    public function testUpdateGeoFiltersInvalidClientIDString() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setClientID('foo');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'client_id' value is negative.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::client_id;
     * @expectedExceptionCode    182584
     */
    public function testUpdateGeoFiltersInvalidClientIDNegative() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setClientID(-5);
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with action 'allow'.
     */
    public function testUpdateGeoFiltersActionAllow() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setAction('allow');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with action 'deny'.
     */
    public function testUpdateGeoFiltersActionDeny() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('delete');
        $filter->setAction('deny');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'action' value is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::action;
     * @expectedExceptionCode    182585
     */
    public function testUpdateGeoFiltersMissingAction() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setAction(null);
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'action' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::action;
     * @expectedExceptionCode    182585
     */
    public function testUpdateGeoFiltersInvalidAction() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setAction('foo');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with category 'master'.
     */
    public function testUpdateGeoFiltersCategoryMaster() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory('master');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with category 'zip'.
     */
    public function testUpdateGeoFiltersCategoryZip() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory('ZIP');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with category 'city'.
     */
    public function testUpdateGeoFiltersCategoryCity() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory('City');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with category 'region'.
     */
    public function testUpdateGeoFiltersCategoryRegion() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory('region');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with category 'country'.
     */
    public function testUpdateGeoFiltersCategoryCountry() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory('cOuNtRy');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'category' value is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::category;
     * @expectedExceptionCode    182586
     */
    public function testUpdateGeoFiltersMissingCategory() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory(null);
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'category' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::category;
     * @expectedExceptionCode    182586
     */
    public function testUpdateGeoFiltersInvalidCategory() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory('foo');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with country 'US'.
     */
    public function testUpdateGeoFiltersCountryUpperCase() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCountry('US');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with country 'us'.
     */
    public function testUpdateGeoFiltersCountryLowerCase() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCountry('us');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with country '-'.
     */
    public function testUpdateGeoFiltersCountry() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCountry('-');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'country' value is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::country;
     * @expectedExceptionCode    182587
     */
    public function testUpdateGeoFiltersMissingCountry() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCountry(null);
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'country' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::country;
     * @expectedExceptionCode    182587
     */
    public function testUpdateGeoFiltersInvalidCountry() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCountry('foo');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * @expectedException        Norse\IPViking\Exception_InvalidRequest
     * @expectedExceptionMessage The IP provided is not a valid IP address.
     * @expectedExceptionCode    182530
     */
    public function xtest() {
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
        $ipq = $this->_ipv->getGeoFilterSettings();
    }

}
