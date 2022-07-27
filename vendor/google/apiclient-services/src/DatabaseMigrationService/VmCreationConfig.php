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

class VmCreationConfig extends \Google\Model
{
  public $subnet;
  public $vmMachineType;
  public $vmZone;

  public function setSubnet($subnet)
  {
    $this->subnet = $subnet;
  }
  public function getSubnet()
  {
    return $this->subnet;
  }
  public function setVmMachineType($vmMachineType)
  {
    $this->vmMachineType = $vmMachineType;
  }
  public function getVmMachineType()
  {
    return $this->vmMachineType;
  }
  public function setVmZone($vmZone)
  {
    $this->vmZone = $vmZone;
  }
  public function getVmZone()
  {
    return $this->vmZone;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(VmCreationConfig::class, 'Google_Service_DatabaseMigrationService_VmCreationConfig');
