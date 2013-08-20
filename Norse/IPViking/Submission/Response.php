<?php

namespace Norse\IPViking;

class Submission_Response extends Response {

    public function __construct($curl_response, $curl_info = null) {
<<<<<<< HEAD
        parent::__construct($curl_response, $curl_info);
=======
        parent::__construct($curl_info);
>>>>>>> 3885058e3555734bab4a8182ee78508587f59ebd

        // We expect curl_response to be empty
    }

}
