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

class NegativeKeywordList extends \Google\Model
{
  public $advertiserId;
  public $displayName;
  public $name;
  public $negativeKeywordListId;
  public $targetedLineItemCount;

  public function setAdvertiserId($advertiserId)
  {
    $this->advertiserId = $advertiserId;
  }
  public function getAdvertiserId()
  {
    return $this->advertiserId;
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
  public function setNegativeKeywordListId($negativeKeywordListId)
  {
    $this->negativeKeywordListId = $negativeKeywordListId;
  }
  public function getNegativeKeywordListId()
  {
    return $this->negativeKeywordListId;
  }
  public function setTargetedLineItemCount($targetedLineItemCount)
  {
    $this->targetedLineItemCount = $targetedLineItemCount;
  }
  public function getTargetedLineItemCount()
  {
    return $this->targetedLineItemCount;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(NegativeKeywordList::class, 'Google_Service_DisplayVideo_NegativeKeywordList');
