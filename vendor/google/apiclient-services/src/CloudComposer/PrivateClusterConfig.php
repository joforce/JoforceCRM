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

namespace Google\Service\CloudComposer;

class PrivateClusterConfig extends \Google\Model
{
  public $enablePrivateEndpoint;
  public $masterIpv4CidrBlock;
  public $masterIpv4ReservedRange;

  public function setEnablePrivateEndpoint($enablePrivateEndpoint)
  {
    $this->enablePrivateEndpoint = $enablePrivateEndpoint;
  }
  public function getEnablePrivateEndpoint()
  {
    return $this->enablePrivateEndpoint;
  }
  public function setMasterIpv4CidrBlock($masterIpv4CidrBlock)
  {
    $this->masterIpv4CidrBlock = $masterIpv4CidrBlock;
  }
  public function getMasterIpv4CidrBlock()
  {
    return $this->masterIpv4CidrBlock;
  }
  public function setMasterIpv4ReservedRange($masterIpv4ReservedRange)
  {
    $this->masterIpv4ReservedRange = $masterIpv4ReservedRange;
  }
  public function getMasterIpv4ReservedRange()
  {
    return $this->masterIpv4ReservedRange;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PrivateClusterConfig::class, 'Google_Service_CloudComposer_PrivateClusterConfig');
