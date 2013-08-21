<?php

require_once __DIR__ . '/../vendor/autoload.php';

class IPVikingTestConfig extends PHPUnit_Framework_TestCase {

    public function testPHPUnit() {
        return true;
    }

    public function testAutoLoad() {
        $this->assertInstanceOf('Norse\IPViking\Exception', new Norse\IPViking\Exception());
    }

    protected function _verifyIPVDefaults(Norse\IPViking $ipv) {
        $this->assertEquals('http://beta.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals('8292777557e8eb8bc169c2af29e87ac07d0f1ac4857048044402dbee06ba5cea', $ipv->getApiKey());
        $this->assertEquals('Norse\IPViking\curl', $ipv->getCurlClass());
    }

    public function testLoadDefaultConfiguration() {
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking());
        $this->_verifyIPVDefaults($ipv);
    }

    public function testLoadConfigurationArrayEmptyIsDefault() {
        $config = array();
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));
        $this->_verifyIPVDefaults($ipv);
    }

    public function testLoadConfigurationArrayProxySandboxWithoutAPIKey() {
        $config = array('proxy' => 'sandbox');
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://beta.ipviking.com/api/', $ipv->getProxy());
    }

    public function testLoadConfigurationArrayProxySandbox() {
        $config = array(
            'proxy'   => 'sandbox',
            'api_key' => 'testLoadConfigurationArrayProxySandbox',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://beta.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
    }

    /**
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Missing or invalid API key.  A valid API key must be provided for any proxy other than the sandbox.
     * @expectedExceptionCode    182502
     */
    public function testLoadConfigurationArrayProxyNotSandboxWithoutAPIKey() {
        $config = array('proxy' => 'universal');
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://api.ipviking.com/api/', $ipv->getProxy());
    }

    public function testLoadConfigurationArrayProxyUniversal() {
        $config = array(
            'proxy'   => 'universal',
            'api_key' => 'testLoadConfigurationArrayProxyUniversal',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://api.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
    }

    public function testLoadConfigurationArrayProxyNorthAmerica() {
        $config = array(
            'proxy'   => 'NorthAmerica',
            'api_key' => 'testLoadConfigurationArrayProxyNorthAmerica',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://us.api.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
    }

    public function testLoadConfigurationArrayProxyEurope() {
        $config = array(
            'proxy'   => 'EUROPE',
            'api_key' => 'testLoadConfigurationArrayProxyEurope',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://eu.api.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
    }

    public function testLoadConfigurationArrayProxyAsiaPacific() {
        $config = array(
            'proxy'   => 'asiaPacific',
            'api_key' => 'testLoadConfigurationArrayProxyAsiaPacific',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://as.api.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
    }

    public function testLoadConfigurationArrayProxySouthAmerica() {
        $config = array(
            'proxy'   => 'SouthAmerica',
            'api_key' => 'testLoadConfigurationArrayProxySouthAmerica',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://la.api.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
    }

    public function testLoadConfigurationArrayProxyUniversalByValue() {
        $config = array(
            'proxy'   => 'http://api.ipviking.com/api/',
            'api_key' => 'testLoadConfigurationArrayProxyUniversalByValue',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals($config['proxy'],   $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
    }

    public function testLoadConfigurationArrayProxyProcessUrl() {
        $config = array(
            'proxy'   => 'https://user:pass@example.com:443/path?query=val#fragment',
            'api_key' => 'testLoadConfigurationArrayProxyProcessUrl',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals($config['proxy'],   $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
    }

    /**
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Proxy value provided is not a valid URL.
     * @expectedExceptionCode    182508
     */
    public function testLoadConfigurationArrayProxyProcessUrlNotUrl() {
        $config = array(
            'proxy'   => 'http:///user@:-9/',
            'api_key' => 'testLoadConfigurationArrayProxyProcessUrlNotUrl',
        );
        $ipv = new Norse\IPViking($config);
    }

    /**
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Unable to process proxy designation, check documentation.
     * @expectedExceptionCode    182501
     */
    public function testLoadConfigurationArrayProxyProcessUrlUnableToProcess() {
        $config = array(
            'proxy'   => array('universal'),
            'api_key' => 'testLoadConfigurationArrayProxyUnableToProcess',
        );
        $ipv = new Norse\IPViking($config);
    }

    /**
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Cannot determine proxy host value, check URL.
     * @expectedExceptionCode    182509
     */
    public function testLoadConfigurationArrayProxyProcessUrlNoHostValue() {
        $config = array(
            'proxy'   => 'notaurl',
            'api_key' => 'testLoadConfigurationArrayProxyProcessUrlNoHostValue',
        );
        $ipv = new Norse\IPViking($config);
    }

    /**
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Unable to locate config file, check path.
     * @expectedExceptionCode    182503
     */
    public function testLoadConfigurationIniNofile() {
        $file = __DIR__ . '/noexist.ini';
        $ipv = new Norse\IPViking($file);
    }

    /**
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Unable to read config file, check permissions.
     * @expectedExceptionCode    182504
     */
    public function testLoadConfigurationIniNotreadable() {
        $file = __DIR__ . '/notreadable.ini';
        chmod($file, 000);

        $ipv = new Norse\IPViking($file);
        chmod($file, 644);
    }

    /**
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Unable to locate config file, directory path given.
     * @expectedExceptionCode    182505
     */
    public function testLoadConfigurationIniIsDirectory() {
        $file = __DIR__ . '/';
        $ipv = new Norse\IPViking($file);
    }

    // I do not know know to re-create 182506, 182507

    public function testLoadCOnfigurationIni() {
        $file = __DIR__ . '/config.ini';
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($file));

        $this->assertEquals('http://us.api.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals('asdf', $ipv->getApiKey());
        $this->assertEquals('Norse\IPViking\curl', $ipv->getCurlClass());
    }

}
