<?php
/**
 * An object representation of IPViking IPQ data.
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

class IPQ_Entry {
    protected $_category_id;
    protected $_category_name;
    protected $_category_factor;
    protected $_protocol_id;
    protected $_protocol_name;
    protected $_overall_protocol;
    protected $_last_active;

    public function __construct($entry) {
        if (isset($entry->category_id))      $this->setCategoryID($entry->category_id);
        if (isset($entry->category_name))    $this->setCategoryName($entry->category_name);
        if (isset($entry->category_factor))  $this->setCategoryFactor($entry->category_factor);
        if (isset($entry->protocol_id))      $this->setProtocolID($entry->protocol_id);
        if (isset($entry->protocol_name))    $this->setProtocolName($entry->protocol_name);
        if (isset($entry->overall_protocol)) $this->setOverallProtocol($entry->overall_protocol);
        if (isset($entry->last_active))      $this->setLastActive($entry->last_active);
    }


    /**
     * Basic accessor methods.
     */

    public function setCategoryID($category_id) {
        $this->_category_id = $category_id;
    }

    public function getCategoryID() {
        return $this->_category_id;
    }

    public function setCategoryName($category_name) {
        $this->_category_name = $category_name;
    }

    public function getCategoryName() {
        return $this->_category_name;
    }

    public function setCategoryFactor($category_factor) {
        $this->_category_factor = $category_factor;
    }

    public function getCategoryFactor() {
        return $this->_category_factor;
    }

    public function setProtocolID($protocol_id) {
        $this->_protocol_id = $protocol_id;
    }

    public function getProtocolID() {
        return $this->_protocol_id;
    }

    public function setProtocolName($protocol_name) {
        $this->_protocol_name = $protocol_name;
    }

    public function getProtocolName() {
        return $this->_protocol_name;
    }

    public function setOverallProtocol($overall_protocol) {
        $this->_overall_protocol = $overall_protocol;
    }

    public function getOverallProtocol() {
        return $this->_overall_protocol;
    }

    public function setLastActive($last_active) {
        $this->_last_active = $last_active;
    }

    public function getLastActive() {
        return $this->_last_active;
    }

}
