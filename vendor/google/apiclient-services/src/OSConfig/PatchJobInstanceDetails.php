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

class PatchJobInstanceDetails extends \Google\Model
{
  public $attemptCount;
  public $failureReason;
  public $instanceSystemId;
  public $name;
  public $state;

  public function setAttemptCount($attemptCount)
  {
    $this->attemptCount = $attemptCount;
  }
  public function getAttemptCount()
  {
    return $this->attemptCount;
  }
  public function setFailureReason($failureReason)
  {
    $this->failureReason = $failureReason;
  }
  public function getFailureReason()
  {
    return $this->failureReason;
  }
  public function setInstanceSystemId($instanceSystemId)
  {
    $this->instanceSystemId = $instanceSystemId;
  }
  public function getInstanceSystemId()
  {
    return $this->instanceSystemId;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setState($state)
  {
    $this->state = $state;
  }
  public function getState()
  {
    return $this->state;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PatchJobInstanceDetails::class, 'Google_Service_OSConfig_PatchJobInstanceDetails');
