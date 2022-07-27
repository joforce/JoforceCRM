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

namespace Google\Service\Dataproc;

class InstanceGroupAutoscalingPolicyConfig extends \Google\Model
{
  public $maxInstances;
  public $minInstances;
  public $weight;

  public function setMaxInstances($maxInstances)
  {
    $this->maxInstances = $maxInstances;
  }
  public function getMaxInstances()
  {
    return $this->maxInstances;
  }
  public function setMinInstances($minInstances)
  {
    $this->minInstances = $minInstances;
  }
  public function getMinInstances()
  {
    return $this->minInstances;
  }
  public function setWeight($weight)
  {
    $this->weight = $weight;
  }
  public function getWeight()
  {
    return $this->weight;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(InstanceGroupAutoscalingPolicyConfig::class, 'Google_Service_Dataproc_InstanceGroupAutoscalingPolicyConfig');
