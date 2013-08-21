<?php

require_once __DIR__ . '/../vendor/autoload.php';

class IPVikingTest extends PHPUnit_Framework_TestCase {

    public function testPHPUnit() {
        return true;
    }

    public function testAutoLoad() {
        $this->assertInstanceOf('Exception', new Norse\IPViking\Exception());
    }

}
