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

class CheckUpgradeResponse extends \Google\Model
{
  public $buildLogUri;
  public $containsPypiModulesConflict;
  public $imageVersion;
  public $pypiConflictBuildLogExtract;
  public $pypiDependencies;

  public function setBuildLogUri($buildLogUri)
  {
    $this->buildLogUri = $buildLogUri;
  }
  public function getBuildLogUri()
  {
    return $this->buildLogUri;
  }
  public function setContainsPypiModulesConflict($containsPypiModulesConflict)
  {
    $this->containsPypiModulesConflict = $containsPypiModulesConflict;
  }
  public function getContainsPypiModulesConflict()
  {
    return $this->containsPypiModulesConflict;
  }
  public function setImageVersion($imageVersion)
  {
    $this->imageVersion = $imageVersion;
  }
  public function getImageVersion()
  {
    return $this->imageVersion;
  }
  public function setPypiConflictBuildLogExtract($pypiConflictBuildLogExtract)
  {
    $this->pypiConflictBuildLogExtract = $pypiConflictBuildLogExtract;
  }
  public function getPypiConflictBuildLogExtract()
  {
    return $this->pypiConflictBuildLogExtract;
  }
  public function setPypiDependencies($pypiDependencies)
  {
    $this->pypiDependencies = $pypiDependencies;
  }
  public function getPypiDependencies()
  {
    return $this->pypiDependencies;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CheckUpgradeResponse::class, 'Google_Service_CloudComposer_CheckUpgradeResponse');
