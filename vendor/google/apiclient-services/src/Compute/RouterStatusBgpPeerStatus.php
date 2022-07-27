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

class RouterStatusBgpPeerStatus extends \Google\Collection
{
  protected $collection_key = 'advertisedRoutes';
  protected $advertisedRoutesType = Route::class;
  protected $advertisedRoutesDataType = 'array';
  protected $bfdStatusType = BfdStatus::class;
  protected $bfdStatusDataType = '';
  public $ipAddress;
  public $linkedVpnTunnel;
  public $name;
  public $numLearnedRoutes;
  public $peerIpAddress;
  public $routerApplianceInstance;
  public $state;
  public $status;
  public $uptime;
  public $uptimeSeconds;

  /**
   * @param Route[]
   */
  public function setAdvertisedRoutes($advertisedRoutes)
  {
    $this->advertisedRoutes = $advertisedRoutes;
  }
  /**
   * @return Route[]
   */
  public function getAdvertisedRoutes()
  {
    return $this->advertisedRoutes;
  }
  /**
   * @param BfdStatus
   */
  public function setBfdStatus(BfdStatus $bfdStatus)
  {
    $this->bfdStatus = $bfdStatus;
  }
  /**
   * @return BfdStatus
   */
  public function getBfdStatus()
  {
    return $this->bfdStatus;
  }
  public function setIpAddress($ipAddress)
  {
    $this->ipAddress = $ipAddress;
  }
  public function getIpAddress()
  {
    return $this->ipAddress;
  }
  public function setLinkedVpnTunnel($linkedVpnTunnel)
  {
    $this->linkedVpnTunnel = $linkedVpnTunnel;
  }
  public function getLinkedVpnTunnel()
  {
    return $this->linkedVpnTunnel;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setNumLearnedRoutes($numLearnedRoutes)
  {
    $this->numLearnedRoutes = $numLearnedRoutes;
  }
  public function getNumLearnedRoutes()
  {
    return $this->numLearnedRoutes;
  }
  public function setPeerIpAddress($peerIpAddress)
  {
    $this->peerIpAddress = $peerIpAddress;
  }
  public function getPeerIpAddress()
  {
    return $this->peerIpAddress;
  }
  public function setRouterApplianceInstance($routerApplianceInstance)
  {
    $this->routerApplianceInstance = $routerApplianceInstance;
  }
  public function getRouterApplianceInstance()
  {
    return $this->routerApplianceInstance;
  }
  public function setState($state)
  {
    $this->state = $state;
  }
  public function getState()
  {
    return $this->state;
  }
  public function setStatus($status)
  {
    $this->status = $status;
  }
  public function getStatus()
  {
    return $this->status;
  }
  public function setUptime($uptime)
  {
    $this->uptime = $uptime;
  }
  public function getUptime()
  {
    return $this->uptime;
  }
  public function setUptimeSeconds($uptimeSeconds)
  {
    $this->uptimeSeconds = $uptimeSeconds;
  }
  public function getUptimeSeconds()
  {
    return $this->uptimeSeconds;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RouterStatusBgpPeerStatus::class, 'Google_Service_Compute_RouterStatusBgpPeerStatus');
