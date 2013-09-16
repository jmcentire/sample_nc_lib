<?php
/**
 * This class provides a wrapper curl.
 *
 * @package Norse
 * @subpackage IPViking
 * @author Jeremy McEntire
 * @version 1.0
 *
 * Copyright (c) 2013, Norse Corp
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in the
 *     documentation and/or other materials provided with the distribution.
 *   * Neither the name of the <organization> nor the
 *     names of its contributors may be used to endorse or promote products
 *     derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace Norse\IPViking;

/**
 * This wrapper requires the curl extension is loaded and available.
 */
if (!in_array('curl', get_loaded_extensions()) || !function_exists('curl_version')) {
    throw new Exception_Curl('cURL extension not found.  Check PHP configuration.', 182510);
}

class Curl implements CurlInterface {
    /* The current cURL handle */
    protected $_handle;

    /**
     * Wrapper for curl_init()
     *
     * @param $url null|string The target URL for the cURL connection.
     *
     * @return resource The result of curl_init() -- it should be a curl_handle.
     */
    public function init($url = null) {
        $this->_handle = curl_init($url);
        return $this->_handle;
    }

    /**
     * Wrapper for curl_setopt()
     *
     * @param $option One of the defined CURLOPT constants.
     * @param $value A value defined for the given CURLOPT.
     *
     * @return boolean The result of curl_setopt()
     */
    public function setOpt($option, $value) {
        return curl_setopt($this->_handle, $option, $value);
    }

    /**
     * Wrapper for curl_setopt_array()
     *
     * @param array $options key->value pairs corresponding to CURLOPT->value settings.
     *
     * @return boolean The result of curl_setopt_array()
     */
    public function setOptArray(array $options) {
        return curl_setopt_array($this->_handle, $options);
    }

    /**
     * Wrapper for curl_exec()
     *
     * @return mixed The result of curl_exec()
     */
    public function exec() {
        return curl_exec($this->_handle);
    }

    /**
     * Wrapper for curl_getinfo()
     *
     * @return array The result of curl_getinfo()
     */
    public function getInfo() {
        return curl_getinfo($this->_handle);
    }

    /**
     * Wrapper for curl_error()
     *
     * @return string The result of curl_error()
     */
    public function getLastError() {
        return curl_error($this->_handle);
    }

    /**
     * Wrapper for curl_errno()
     *
     * @return int The result of curl_errno()
     */
    public function getLastErrorNo() {
        return curl_errno($this->_handle);
    }

    /**
     * Wrapper for curl_close()
     *
     * @return bool The result of curl_close()
     */
    public function close() {
        return curl_close($this->_handle);
    }

}
