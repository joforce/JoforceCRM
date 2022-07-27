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

namespace Google\Service\GKEHub;

class ConfigManagementErrorResource extends \Google\Model
{
  protected $resourceGvkType = ConfigManagementGroupVersionKind::class;
  protected $resourceGvkDataType = '';
  public $resourceName;
  public $resourceNamespace;
  public $sourcePath;

  /**
   * @param ConfigManagementGroupVersionKind
   */
  public function setResourceGvk(ConfigManagementGroupVersionKind $resourceGvk)
  {
    $this->resourceGvk = $resourceGvk;
  }
  /**
   * @return ConfigManagementGroupVersionKind
   */
  public function getResourceGvk()
  {
    return $this->resourceGvk;
  }
  public function setResourceName($resourceName)
  {
    $this->resourceName = $resourceName;
  }
  public function getResourceName()
  {
    return $this->resourceName;
  }
  public function setResourceNamespace($resourceNamespace)
  {
    $this->resourceNamespace = $resourceNamespace;
  }
  public function getResourceNamespace()
  {
    return $this->resourceNamespace;
  }
  public function setSourcePath($sourcePath)
  {
    $this->sourcePath = $sourcePath;
  }
  public function getSourcePath()
  {
    return $this->sourcePath;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ConfigManagementErrorResource::class, 'Google_Service_GKEHub_ConfigManagementErrorResource');
