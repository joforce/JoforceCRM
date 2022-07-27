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

namespace Google\Service\Monitoring;

class Service extends \Google\Model
{
  protected $appEngineType = AppEngine::class;
  protected $appEngineDataType = '';
  protected $cloudEndpointsType = CloudEndpoints::class;
  protected $cloudEndpointsDataType = '';
  protected $clusterIstioType = ClusterIstio::class;
  protected $clusterIstioDataType = '';
  protected $customType = Custom::class;
  protected $customDataType = '';
  public $displayName;
  protected $istioCanonicalServiceType = IstioCanonicalService::class;
  protected $istioCanonicalServiceDataType = '';
  protected $meshIstioType = MeshIstio::class;
  protected $meshIstioDataType = '';
  public $name;
  protected $telemetryType = Telemetry::class;
  protected $telemetryDataType = '';
  public $userLabels;

  /**
   * @param AppEngine
   */
  public function setAppEngine(AppEngine $appEngine)
  {
    $this->appEngine = $appEngine;
  }
  /**
   * @return AppEngine
   */
  public function getAppEngine()
  {
    return $this->appEngine;
  }
  /**
   * @param CloudEndpoints
   */
  public function setCloudEndpoints(CloudEndpoints $cloudEndpoints)
  {
    $this->cloudEndpoints = $cloudEndpoints;
  }
  /**
   * @return CloudEndpoints
   */
  public function getCloudEndpoints()
  {
    return $this->cloudEndpoints;
  }
  /**
   * @param ClusterIstio
   */
  public function setClusterIstio(ClusterIstio $clusterIstio)
  {
    $this->clusterIstio = $clusterIstio;
  }
  /**
   * @return ClusterIstio
   */
  public function getClusterIstio()
  {
    return $this->clusterIstio;
  }
  /**
   * @param Custom
   */
  public function setCustom(Custom $custom)
  {
    $this->custom = $custom;
  }
  /**
   * @return Custom
   */
  public function getCustom()
  {
    return $this->custom;
  }
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  public function getDisplayName()
  {
    return $this->displayName;
  }
  /**
   * @param IstioCanonicalService
   */
  public function setIstioCanonicalService(IstioCanonicalService $istioCanonicalService)
  {
    $this->istioCanonicalService = $istioCanonicalService;
  }
  /**
   * @return IstioCanonicalService
   */
  public function getIstioCanonicalService()
  {
    return $this->istioCanonicalService;
  }
  /**
   * @param MeshIstio
   */
  public function setMeshIstio(MeshIstio $meshIstio)
  {
    $this->meshIstio = $meshIstio;
  }
  /**
   * @return MeshIstio
   */
  public function getMeshIstio()
  {
    return $this->meshIstio;
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
   * @param Telemetry
   */
  public function setTelemetry(Telemetry $telemetry)
  {
    $this->telemetry = $telemetry;
  }
  /**
   * @return Telemetry
   */
  public function getTelemetry()
  {
    return $this->telemetry;
  }
  public function setUserLabels($userLabels)
  {
    $this->userLabels = $userLabels;
  }
  public function getUserLabels()
  {
    return $this->userLabels;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Service::class, 'Google_Service_Monitoring_Service');
