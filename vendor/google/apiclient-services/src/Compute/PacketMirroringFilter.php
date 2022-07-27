<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\Compute;

class PacketMirroringFilter extends \Google\Collection
{
  protected $collection_key = 'cidrRanges';
  protected $internal_gapi_mappings = [
        "iPProtocols" => "IPProtocols",
  ];
  public $iPProtocols;
  public $cidrRanges;
  public $direction;

  public function setIPProtocols($iPProtocols)
  {
    $this->iPProtocols = $iPProtocols;
  }
  public function getIPProtocols()
  {
    return $this->iPProtocols;
  }
  public function setCidrRanges($cidrRanges)
  {
    $this->cidrRanges = $cidrRanges;
  }
  public function getCidrRanges()
  {
    return $this->cidrRanges;
  }
  public function setDirection($direction)
  {
    $this->direction = $direction;
  }
  public function getDirection()
  {
    return $this->direction;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PacketMirroringFilter::class, 'Google_Service_Compute_PacketMirroringFilter');
