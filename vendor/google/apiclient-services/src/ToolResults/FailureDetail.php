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

namespace Google\Service\ToolResults;

class FailureDetail extends \Google\Model
{
  public $crashed;
  public $deviceOutOfMemory;
  public $failedRoboscript;
  public $notInstalled;
  public $otherNativeCrash;
  public $timedOut;
  public $unableToCrawl;

  public function setCrashed($crashed)
  {
    $this->crashed = $crashed;
  }
  public function getCrashed()
  {
    return $this->crashed;
  }
  public function setDeviceOutOfMemory($deviceOutOfMemory)
  {
    $this->deviceOutOfMemory = $deviceOutOfMemory;
  }
  public function getDeviceOutOfMemory()
  {
    return $this->deviceOutOfMemory;
  }
  public function setFailedRoboscript($failedRoboscript)
  {
    $this->failedRoboscript = $failedRoboscript;
  }
  public function getFailedRoboscript()
  {
    return $this->failedRoboscript;
  }
  public function setNotInstalled($notInstalled)
  {
    $this->notInstalled = $notInstalled;
  }
  public function getNotInstalled()
  {
    return $this->notInstalled;
  }
  public function setOtherNativeCrash($otherNativeCrash)
  {
    $this->otherNativeCrash = $otherNativeCrash;
  }
  public function getOtherNativeCrash()
  {
    return $this->otherNativeCrash;
  }
  public function setTimedOut($timedOut)
  {
    $this->timedOut = $timedOut;
  }
  public function getTimedOut()
  {
    return $this->timedOut;
  }
  public function setUnableToCrawl($unableToCrawl)
  {
    $this->unableToCrawl = $unableToCrawl;
  }
  public function getUnableToCrawl()
  {
    return $this->unableToCrawl;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(FailureDetail::class, 'Google_Service_ToolResults_FailureDetail');
