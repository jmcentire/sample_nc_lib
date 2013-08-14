<?php

include './Norse/IPViking.php';
include './Norse/IPViking/Request.php';
include './Norse/IPViking/Exception.php';

include './Norse/IPViking/IPQ/Request.php';
include './Norse/IPViking/IPQ/Response.php';
include './Norse/IPViking/IPQ/IPInfo.php';
include './Norse/IPViking/IPQ/GeoLoc.php';
include './Norse/IPViking/IPQ/Entry.php';
include './Norse/IPViking/IPQ/FactoringReason.php';

include './Norse/IPViking/Submission/Request.php';
include './Norse/IPViking/Submission/Response.php';

include './Norse/IPViking/Settings/GeoFilter.php';
include './Norse/IPViking/Settings/GeoFilter/Filter.php';
include './Norse/IPViking/Settings/GeoFilter/Collection.php';

include './Norse/IPViking/Settings/RiskFactor.php';
include './Norse/IPViking/Settings/RiskFactor/Factor.php';
include './Norse/IPViking/Settings/RiskFactor/Collection.php';

include './Norse/IPViking/Exception/InvalidConfig.php';



// $config = array();
// $config = array('proxy' => 'universal', 'api_key' => '1234');
$config = array('proxy' => 'http://jmc.mac/index.php', 'api_key' => '1234');
// $config = './test_config.ini';
$ipv = new Norse\IPViking($config);


$riskFactors = $ipv->getRiskFactorSettings();
var_dump($riskFactors);



// $geoFilters = $ipv->getGeoFilterSettings();
//var_dump($geoFilters);



// $sub = $ipv->submission('10.0.0.1', 7, 51, time());
// var_dump($sub);



// $ipq = $ipv->ipq('10.0.0.1');
// var_dump($ipq->getRiskColor());



// $ipqr = $ipv->getIPQRequest('10.0.0.1');

// $ipqr->setTransID('1234');
// $ipqr->setClientID('3456');
// $ipqr->setCustomID('asdf');

// $ipqr->setCategories(array(1, 2, 3, 4));
// $ipqr->setOptions(array('noresolve', 'supress', 'compress'));

// $ipqr->setAddress('1049 Market Street');
// $ipqr->setCity('San Francisco');
// $ipqr->setZip(94103);
// $ipqr->setState('California');
// $ipqr->setCountry('US');

// $ipq = $ipqr->exec();
//var_dump($ipq->getRiskColor());
