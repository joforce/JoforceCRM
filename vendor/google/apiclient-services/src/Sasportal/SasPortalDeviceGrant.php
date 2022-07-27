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

namespace Google\Service\Sasportal;

class SasPortalDeviceGrant extends \Google\Collection
{
  protected $collection_key = 'suspensionReason';
  public $channelType;
  public $expireTime;
  protected $frequencyRangeType = SasPortalFrequencyRange::class;
  protected $frequencyRangeDataType = '';
  public $grantId;
  public $maxEirp;
  protected $moveListType = SasPortalDpaMoveList::class;
  protected $moveListDataType = 'array';
  public $state;
  public $suspensionReason;

  public function setChannelType($channelType)
  {
    $this->channelType = $channelType;
  }
  public function getChannelType()
  {
    return $this->channelType;
  }
  public function setExpireTime($expireTime)
  {
    $this->expireTime = $expireTime;
  }
  public function getExpireTime()
  {
    return $this->expireTime;
  }
  /**
   * @param SasPortalFrequencyRange
   */
  public function setFrequencyRange(SasPortalFrequencyRange $frequencyRange)
  {
    $this->frequencyRange = $frequencyRange;
  }
  /**
   * @return SasPortalFrequencyRange
   */
  public function getFrequencyRange()
  {
    return $this->frequencyRange;
  }
  public function setGrantId($grantId)
  {
    $this->grantId = $grantId;
  }
  public function getGrantId()
  {
    return $this->grantId;
  }
  public function setMaxEirp($maxEirp)
  {
    $this->maxEirp = $maxEirp;
  }
  public function getMaxEirp()
  {
    return $this->maxEirp;
  }
  /**
   * @param SasPortalDpaMoveList[]
   */
  public function setMoveList($moveList)
  {
    $this->moveList = $moveList;
  }
  /**
   * @return SasPortalDpaMoveList[]
   */
  public function getMoveList()
  {
    return $this->moveList;
  }
  public function setState($state)
  {
    $this->state = $state;
  }
  public function getState()
  {
    return $this->state;
  }
  public function setSuspensionReason($suspensionReason)
  {
    $this->suspensionReason = $suspensionReason;
  }
  public function getSuspensionReason()
  {
    return $this->suspensionReason;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SasPortalDeviceGrant::class, 'Google_Service_Sasportal_SasPortalDeviceGrant');
