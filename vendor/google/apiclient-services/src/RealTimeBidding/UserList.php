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

namespace Google\Service\RealTimeBidding;

class UserList extends \Google\Model
{
  public $description;
  public $displayName;
  public $membershipDurationDays;
  public $name;
  public $status;
  protected $urlRestrictionType = UrlRestriction::class;
  protected $urlRestrictionDataType = '';

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
  public function setMembershipDurationDays($membershipDurationDays)
  {
    $this->membershipDurationDays = $membershipDurationDays;
  }
  public function getMembershipDurationDays()
  {
    return $this->membershipDurationDays;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setStatus($status)
  {
    $this->status = $status;
  }
  public function getStatus()
  {
    return $this->status;
  }
  /**
   * @param UrlRestriction
   */
  public function setUrlRestriction(UrlRestriction $urlRestriction)
  {
    $this->urlRestriction = $urlRestriction;
  }
  /**
   * @return UrlRestriction
   */
  public function getUrlRestriction()
  {
    return $this->urlRestriction;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(UserList::class, 'Google_Service_RealTimeBidding_UserList');
