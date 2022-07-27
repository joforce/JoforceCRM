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

namespace Google\Service\Iam;

class WorkloadIdentityPoolProvider extends \Google\Model
{
  public $attributeCondition;
  public $attributeMapping;
  protected $awsType = Aws::class;
  protected $awsDataType = '';
  public $description;
  public $disabled;
  public $displayName;
  public $name;
  protected $oidcType = Oidc::class;
  protected $oidcDataType = '';
  public $state;

  public function setAttributeCondition($attributeCondition)
  {
    $this->attributeCondition = $attributeCondition;
  }
  public function getAttributeCondition()
  {
    return $this->attributeCondition;
  }
  public function setAttributeMapping($attributeMapping)
  {
    $this->attributeMapping = $attributeMapping;
  }
  public function getAttributeMapping()
  {
    return $this->attributeMapping;
  }
  /**
   * @param Aws
   */
  public function setAws(Aws $aws)
  {
    $this->aws = $aws;
  }
  /**
   * @return Aws
   */
  public function getAws()
  {
    return $this->aws;
  }
  public function setDescription($description)
  {
    $this->description = $description;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function setDisabled($disabled)
  {
    $this->disabled = $disabled;
  }
  public function getDisabled()
  {
    return $this->disabled;
  }
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  public function getDisplayName()
  {
    return $this->displayName;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param Oidc
   */
  public function setOidc(Oidc $oidc)
  {
    $this->oidc = $oidc;
  }
  /**
   * @return Oidc
   */
  public function getOidc()
  {
    return $this->oidc;
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
class_alias(WorkloadIdentityPoolProvider::class, 'Google_Service_Iam_WorkloadIdentityPoolProvider');
