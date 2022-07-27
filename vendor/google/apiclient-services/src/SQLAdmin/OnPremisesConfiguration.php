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

namespace Google\Service\SQLAdmin;

class OnPremisesConfiguration extends \Google\Model
{
  public $caCertificate;
  public $clientCertificate;
  public $clientKey;
  public $dumpFilePath;
  public $hostPort;
  public $kind;
  public $password;
  protected $sourceInstanceType = InstanceReference::class;
  protected $sourceInstanceDataType = '';
  public $username;

  public function setCaCertificate($caCertificate)
  {
    $this->caCertificate = $caCertificate;
  }
  public function getCaCertificate()
  {
    return $this->caCertificate;
  }
  public function setClientCertificate($clientCertificate)
  {
    $this->clientCertificate = $clientCertificate;
  }
  public function getClientCertificate()
  {
    return $this->clientCertificate;
  }
  public function setClientKey($clientKey)
  {
    $this->clientKey = $clientKey;
  }
  public function getClientKey()
  {
    return $this->clientKey;
  }
  public function setDumpFilePath($dumpFilePath)
  {
    $this->dumpFilePath = $dumpFilePath;
  }
  public function getDumpFilePath()
  {
    return $this->dumpFilePath;
  }
  public function setHostPort($hostPort)
  {
    $this->hostPort = $hostPort;
  }
  public function getHostPort()
  {
    return $this->hostPort;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setPassword($password)
  {
    $this->password = $password;
  }
  public function getPassword()
  {
    return $this->password;
  }
  /**
   * @param InstanceReference
   */
  public function setSourceInstance(InstanceReference $sourceInstance)
  {
    $this->sourceInstance = $sourceInstance;
  }
  /**
   * @return InstanceReference
   */
  public function getSourceInstance()
  {
    return $this->sourceInstance;
  }
  public function setUsername($username)
  {
    $this->username = $username;
  }
  public function getUsername()
  {
    return $this->username;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OnPremisesConfiguration::class, 'Google_Service_SQLAdmin_OnPremisesConfiguration');
