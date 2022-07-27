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

class ForwardingRuleServiceDirectoryRegistration extends \Google\Model
{
  public $namespace;
  public $service;
  public $serviceDirectoryRegion;

  public function setNamespace($namespace)
  {
    $this->namespace = $namespace;
  }
  public function getNamespace()
  {
    return $this->namespace;
  }
  public function setService($service)
  {
    $this->service = $service;
  }
  public function getService()
  {
    return $this->service;
  }
  public function setServiceDirectoryRegion($serviceDirectoryRegion)
  {
    $this->serviceDirectoryRegion = $serviceDirectoryRegion;
  }
  public function getServiceDirectoryRegion()
  {
    return $this->serviceDirectoryRegion;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ForwardingRuleServiceDirectoryRegistration::class, 'Google_Service_Compute_ForwardingRuleServiceDirectoryRegistration');
