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

namespace Google\Service\DisplayVideo;

class GoogleAudience extends \Google\Model
{
  public $displayName;
  public $googleAudienceId;
  public $googleAudienceType;
  public $name;

  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  public function getDisplayName()
  {
    return $this->displayName;
  }
  public function setGoogleAudienceId($googleAudienceId)
  {
    $this->googleAudienceId = $googleAudienceId;
  }
  public function getGoogleAudienceId()
  {
    return $this->googleAudienceId;
  }
  public function setGoogleAudienceType($googleAudienceType)
  {
    $this->googleAudienceType = $googleAudienceType;
  }
  public function getGoogleAudienceType()
  {
    return $this->googleAudienceType;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleAudience::class, 'Google_Service_DisplayVideo_GoogleAudience');
