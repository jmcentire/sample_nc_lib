<?php

namespace Norse\IPViking;

class Settings_GeoFilter {
    protected $_client_id; // clientID ( 0 or higher )
    protected $_action; // action { allow | deny }
    protected $_category; // category { master | zip | city | region | country }
    protected $_country; // country (char 2)
    protected $_region; // region * regions
    protected $_city; // city
    protected $_zip; // zip * int'l zips
}
