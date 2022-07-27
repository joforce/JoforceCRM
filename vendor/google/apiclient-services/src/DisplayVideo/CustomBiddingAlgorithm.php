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

class CustomBiddingAlgorithm extends \Google\Collection
{
  protected $collection_key = 'sharedAdvertiserIds';
  public $advertiserId;
  public $customBiddingAlgorithmId;
  public $customBiddingAlgorithmState;
  public $customBiddingAlgorithmType;
  public $displayName;
  public $entityStatus;
  public $name;
  public $partnerId;
  public $sharedAdvertiserIds;

  public function setAdvertiserId($advertiserId)
  {
    $this->advertiserId = $advertiserId;
  }
  public function getAdvertiserId()
  {
    return $this->advertiserId;
  }
  public function setCustomBiddingAlgorithmId($customBiddingAlgorithmId)
  {
    $this->customBiddingAlgorithmId = $customBiddingAlgorithmId;
  }
  public function getCustomBiddingAlgorithmId()
  {
    return $this->customBiddingAlgorithmId;
  }
  public function setCustomBiddingAlgorithmState($customBiddingAlgorithmState)
  {
    $this->customBiddingAlgorithmState = $customBiddingAlgorithmState;
  }
  public function getCustomBiddingAlgorithmState()
  {
    return $this->customBiddingAlgorithmState;
  }
  public function setCustomBiddingAlgorithmType($customBiddingAlgorithmType)
  {
    $this->customBiddingAlgorithmType = $customBiddingAlgorithmType;
  }
  public function getCustomBiddingAlgorithmType()
  {
    return $this->customBiddingAlgorithmType;
  }
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  public function getDisplayName()
  {
    return $this->displayName;
  }
  public function setEntityStatus($entityStatus)
  {
    $this->entityStatus = $entityStatus;
  }
  public function getEntityStatus()
  {
    return $this->entityStatus;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setPartnerId($partnerId)
  {
    $this->partnerId = $partnerId;
  }
  public function getPartnerId()
  {
    return $this->partnerId;
  }
  public function setSharedAdvertiserIds($sharedAdvertiserIds)
  {
    $this->sharedAdvertiserIds = $sharedAdvertiserIds;
  }
  public function getSharedAdvertiserIds()
  {
    return $this->sharedAdvertiserIds;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CustomBiddingAlgorithm::class, 'Google_Service_DisplayVideo_CustomBiddingAlgorithm');
