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

class FirewallPolicyRuleMatcher extends \Google\Collection
{
  protected $collection_key = 'srcIpRanges';
  public $destIpRanges;
  protected $layer4ConfigsType = FirewallPolicyRuleMatcherLayer4Config::class;
  protected $layer4ConfigsDataType = 'array';
  public $srcIpRanges;

  public function setDestIpRanges($destIpRanges)
  {
    $this->destIpRanges = $destIpRanges;
  }
  public function getDestIpRanges()
  {
    return $this->destIpRanges;
  }
  /**
   * @param FirewallPolicyRuleMatcherLayer4Config[]
   */
  public function setLayer4Configs($layer4Configs)
  {
    $this->layer4Configs = $layer4Configs;
  }
  /**
   * @return FirewallPolicyRuleMatcherLayer4Config[]
   */
  public function getLayer4Configs()
  {
    return $this->layer4Configs;
  }
  public function setSrcIpRanges($srcIpRanges)
  {
    $this->srcIpRanges = $srcIpRanges;
  }
  public function getSrcIpRanges()
  {
    return $this->srcIpRanges;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(FirewallPolicyRuleMatcher::class, 'Google_Service_Compute_FirewallPolicyRuleMatcher');
