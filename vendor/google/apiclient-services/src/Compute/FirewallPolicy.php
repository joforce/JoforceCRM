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

class FirewallPolicy extends \Google\Collection
{
  protected $collection_key = 'rules';
  protected $associationsType = FirewallPolicyAssociation::class;
  protected $associationsDataType = 'array';
  public $creationTimestamp;
  public $description;
  public $displayName;
  public $fingerprint;
  public $id;
  public $kind;
  public $name;
  public $parent;
  public $ruleTupleCount;
  protected $rulesType = FirewallPolicyRule::class;
  protected $rulesDataType = 'array';
  public $selfLink;
  public $selfLinkWithId;
  public $shortName;

  /**
   * @param FirewallPolicyAssociation[]
   */
  public function setAssociations($associations)
  {
    $this->associations = $associations;
  }
  /**
   * @return FirewallPolicyAssociation[]
   */
  public function getAssociations()
  {
    return $this->associations;
  }
  public function setCreationTimestamp($creationTimestamp)
  {
    $this->creationTimestamp = $creationTimestamp;
  }
  public function getCreationTimestamp()
  {
    return $this->creationTimestamp;
  }
  public function setDescription($description)
  {
    $this->description = $description;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  public function getDisplayName()
  {
    return $this->displayName;
  }
  public function setFingerprint($fingerprint)
  {
    $this->fingerprint = $fingerprint;
  }
  public function getFingerprint()
  {
    return $this->fingerprint;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setParent($parent)
  {
    $this->parent = $parent;
  }
  public function getParent()
  {
    return $this->parent;
  }
  public function setRuleTupleCount($ruleTupleCount)
  {
    $this->ruleTupleCount = $ruleTupleCount;
  }
  public function getRuleTupleCount()
  {
    return $this->ruleTupleCount;
  }
  /**
   * @param FirewallPolicyRule[]
   */
  public function setRules($rules)
  {
    $this->rules = $rules;
  }
  /**
   * @return FirewallPolicyRule[]
   */
  public function getRules()
  {
    return $this->rules;
  }
  public function setSelfLink($selfLink)
  {
    $this->selfLink = $selfLink;
  }
  public function getSelfLink()
  {
    return $this->selfLink;
  }
  public function setSelfLinkWithId($selfLinkWithId)
  {
    $this->selfLinkWithId = $selfLinkWithId;
  }
  public function getSelfLinkWithId()
  {
    return $this->selfLinkWithId;
  }
  public function setShortName($shortName)
  {
    $this->shortName = $shortName;
  }
  public function getShortName()
  {
    return $this->shortName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(FirewallPolicy::class, 'Google_Service_Compute_FirewallPolicy');
