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

namespace Google\Service\CloudIAP;

class Brand extends \Google\Model
{
  public $applicationTitle;
  public $name;
  public $orgInternalOnly;
  public $supportEmail;

  public function setApplicationTitle($applicationTitle)
  {
    $this->applicationTitle = $applicationTitle;
  }
  public function getApplicationTitle()
  {
    return $this->applicationTitle;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setOrgInternalOnly($orgInternalOnly)
  {
    $this->orgInternalOnly = $orgInternalOnly;
  }
  public function getOrgInternalOnly()
  {
    return $this->orgInternalOnly;
  }
  public function setSupportEmail($supportEmail)
  {
    $this->supportEmail = $supportEmail;
  }
  public function getSupportEmail()
  {
    return $this->supportEmail;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Brand::class, 'Google_Service_CloudIAP_Brand');
