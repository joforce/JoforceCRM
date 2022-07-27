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

namespace Google\Service\Logging;

class LogBucket extends \Google\Collection
{
  protected $collection_key = 'restrictedFields';
  public $createTime;
  public $description;
  public $lifecycleState;
  public $locked;
  public $name;
  public $restrictedFields;
  public $retentionDays;
  public $updateTime;

  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  public function getCreateTime()
  {
    return $this->createTime;
  }
  public function setDescription($description)
  {
    $this->description = $description;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function setLifecycleState($lifecycleState)
  {
    $this->lifecycleState = $lifecycleState;
  }
  public function getLifecycleState()
  {
    return $this->lifecycleState;
  }
  public function setLocked($locked)
  {
    $this->locked = $locked;
  }
  public function getLocked()
  {
    return $this->locked;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setRestrictedFields($restrictedFields)
  {
    $this->restrictedFields = $restrictedFields;
  }
  public function getRestrictedFields()
  {
    return $this->restrictedFields;
  }
  public function setRetentionDays($retentionDays)
  {
    $this->retentionDays = $retentionDays;
  }
  public function getRetentionDays()
  {
    return $this->retentionDays;
  }
  public function setUpdateTime($updateTime)
  {
    $this->updateTime = $updateTime;
  }
  public function getUpdateTime()
  {
    return $this->updateTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(LogBucket::class, 'Google_Service_Logging_LogBucket');
