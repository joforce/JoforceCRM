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

namespace Google\Service\OSConfig;

class WindowsUpdateSettings extends \Google\Collection
{
  protected $collection_key = 'exclusivePatches';
  public $classifications;
  public $excludes;
  public $exclusivePatches;

  public function setClassifications($classifications)
  {
    $this->classifications = $classifications;
  }
  public function getClassifications()
  {
    return $this->classifications;
  }
  public function setExcludes($excludes)
  {
    $this->excludes = $excludes;
  }
  public function getExcludes()
  {
    return $this->excludes;
  }
  public function setExclusivePatches($exclusivePatches)
  {
    $this->exclusivePatches = $exclusivePatches;
  }
  public function getExclusivePatches()
  {
    return $this->exclusivePatches;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(WindowsUpdateSettings::class, 'Google_Service_OSConfig_WindowsUpdateSettings');
