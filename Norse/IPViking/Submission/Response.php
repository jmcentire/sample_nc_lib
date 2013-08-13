<?php

namespace Norse\IPViking;

class Submission_Response {

    public function __construct($curl_response, $curl_info) {
        $this->data = array($curl_response, $curl_info);
    }

}
