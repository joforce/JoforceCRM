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

class PrivateEnvironmentConfig extends \Google\Model
{
  public $cloudComposerNetworkIpv4CidrBlock;
  public $cloudComposerNetworkIpv4ReservedRange;
  public $cloudSqlIpv4CidrBlock;
  public $enablePrivateEnvironment;
  protected $privateClusterConfigType = PrivateClusterConfig::class;
  protected $privateClusterConfigDataType = '';
  public $webServerIpv4CidrBlock;
  public $webServerIpv4ReservedRange;

  public function setCloudComposerNetworkIpv4CidrBlock($cloudComposerNetworkIpv4CidrBlock)
  {
    $this->cloudComposerNetworkIpv4CidrBlock = $cloudComposerNetworkIpv4CidrBlock;
  }
  public function getCloudComposerNetworkIpv4CidrBlock()
  {
    return $this->cloudComposerNetworkIpv4CidrBlock;
  }
  public function setCloudComposerNetworkIpv4ReservedRange($cloudComposerNetworkIpv4ReservedRange)
  {
    $this->cloudComposerNetworkIpv4ReservedRange = $cloudComposerNetworkIpv4ReservedRange;
  }
  public function getCloudComposerNetworkIpv4ReservedRange()
  {
    return $this->cloudComposerNetworkIpv4ReservedRange;
  }
  public function setCloudSqlIpv4CidrBlock($cloudSqlIpv4CidrBlock)
  {
    $this->cloudSqlIpv4CidrBlock = $cloudSqlIpv4CidrBlock;
  }
  public function getCloudSqlIpv4CidrBlock()
  {
    return $this->cloudSqlIpv4CidrBlock;
  }
  public function setEnablePrivateEnvironment($enablePrivateEnvironment)
  {
    $this->enablePrivateEnvironment = $enablePrivateEnvironment;
  }
  public function getEnablePrivateEnvironment()
  {
    return $this->enablePrivateEnvironment;
  }
  /**
   * @param PrivateClusterConfig
   */
  public function setPrivateClusterConfig(PrivateClusterConfig $privateClusterConfig)
  {
    $this->privateClusterConfig = $privateClusterConfig;
  }
  /**
   * @return PrivateClusterConfig
   */
  public function getPrivateClusterConfig()
  {
    return $this->privateClusterConfig;
  }
  public function setWebServerIpv4CidrBlock($webServerIpv4CidrBlock)
  {
    $this->webServerIpv4CidrBlock = $webServerIpv4CidrBlock;
  }
  public function getWebServerIpv4CidrBlock()
  {
    return $this->webServerIpv4CidrBlock;
  }
  public function setWebServerIpv4ReservedRange($webServerIpv4ReservedRange)
  {
    $this->webServerIpv4ReservedRange = $webServerIpv4ReservedRange;
  }
  public function getWebServerIpv4ReservedRange()
  {
    return $this->webServerIpv4ReservedRange;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PrivateEnvironmentConfig::class, 'Google_Service_CloudComposer_PrivateEnvironmentConfig');
