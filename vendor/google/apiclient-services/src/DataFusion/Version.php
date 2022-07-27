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

namespace Google\Service\DataFusion;

class Version extends \Google\Collection
{
  protected $collection_key = 'availableFeatures';
  public $availableFeatures;
  public $defaultVersion;
  public $type;
  public $versionNumber;

  public function setAvailableFeatures($availableFeatures)
  {
    $this->availableFeatures = $availableFeatures;
  }
  public function getAvailableFeatures()
  {
    return $this->availableFeatures;
  }
  public function setDefaultVersion($defaultVersion)
  {
    $this->defaultVersion = $defaultVersion;
  }
  public function getDefaultVersion()
  {
    return $this->defaultVersion;
  }
  public function setType($type)
  {
    $this->type = $type;
  }
  public function getType()
  {
    return $this->type;
  }
  public function setVersionNumber($versionNumber)
  {
    $this->versionNumber = $versionNumber;
  }
  public function getVersionNumber()
  {
    return $this->versionNumber;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Version::class, 'Google_Service_DataFusion_Version');
