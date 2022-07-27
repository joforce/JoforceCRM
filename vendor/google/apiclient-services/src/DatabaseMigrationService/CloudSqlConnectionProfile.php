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

namespace Google\Service\DatabaseMigrationService;

class CloudSqlConnectionProfile extends \Google\Model
{
  public $cloudSqlId;
  public $privateIp;
  public $publicIp;
  protected $settingsType = CloudSqlSettings::class;
  protected $settingsDataType = '';

  public function setCloudSqlId($cloudSqlId)
  {
    $this->cloudSqlId = $cloudSqlId;
  }
  public function getCloudSqlId()
  {
    return $this->cloudSqlId;
  }
  public function setPrivateIp($privateIp)
  {
    $this->privateIp = $privateIp;
  }
  public function getPrivateIp()
  {
    return $this->privateIp;
  }
  public function setPublicIp($publicIp)
  {
    $this->publicIp = $publicIp;
  }
  public function getPublicIp()
  {
    return $this->publicIp;
  }
  /**
   * @param CloudSqlSettings
   */
  public function setSettings(CloudSqlSettings $settings)
  {
    $this->settings = $settings;
  }
  /**
   * @return CloudSqlSettings
   */
  public function getSettings()
  {
    return $this->settings;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CloudSqlConnectionProfile::class, 'Google_Service_DatabaseMigrationService_CloudSqlConnectionProfile');
