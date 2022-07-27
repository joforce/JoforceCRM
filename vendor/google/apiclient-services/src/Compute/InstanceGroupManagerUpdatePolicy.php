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

class InstanceGroupManagerUpdatePolicy extends \Google\Model
{
  public $instanceRedistributionType;
  protected $maxSurgeType = FixedOrPercent::class;
  protected $maxSurgeDataType = '';
  protected $maxUnavailableType = FixedOrPercent::class;
  protected $maxUnavailableDataType = '';
  public $minimalAction;
  public $mostDisruptiveAllowedAction;
  public $replacementMethod;
  public $type;

  public function setInstanceRedistributionType($instanceRedistributionType)
  {
    $this->instanceRedistributionType = $instanceRedistributionType;
  }
  public function getInstanceRedistributionType()
  {
    return $this->instanceRedistributionType;
  }
  /**
   * @param FixedOrPercent
   */
  public function setMaxSurge(FixedOrPercent $maxSurge)
  {
    $this->maxSurge = $maxSurge;
  }
  /**
   * @return FixedOrPercent
   */
  public function getMaxSurge()
  {
    return $this->maxSurge;
  }
  /**
   * @param FixedOrPercent
   */
  public function setMaxUnavailable(FixedOrPercent $maxUnavailable)
  {
    $this->maxUnavailable = $maxUnavailable;
  }
  /**
   * @return FixedOrPercent
   */
  public function getMaxUnavailable()
  {
    return $this->maxUnavailable;
  }
  public function setMinimalAction($minimalAction)
  {
    $this->minimalAction = $minimalAction;
  }
  public function getMinimalAction()
  {
    return $this->minimalAction;
  }
  public function setMostDisruptiveAllowedAction($mostDisruptiveAllowedAction)
  {
    $this->mostDisruptiveAllowedAction = $mostDisruptiveAllowedAction;
  }
  public function getMostDisruptiveAllowedAction()
  {
    return $this->mostDisruptiveAllowedAction;
  }
  public function setReplacementMethod($replacementMethod)
  {
    $this->replacementMethod = $replacementMethod;
  }
  public function getReplacementMethod()
  {
    return $this->replacementMethod;
  }
  public function setType($type)
  {
    $this->type = $type;
  }
  public function getType()
  {
    return $this->type;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(InstanceGroupManagerUpdatePolicy::class, 'Google_Service_Compute_InstanceGroupManagerUpdatePolicy');
