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

namespace Google\Service\Dataflow;

class Environment extends \Google\Collection
{
  protected $collection_key = 'workerPools';
  public $clusterManagerApiService;
  public $dataset;
  protected $debugOptionsType = DebugOptions::class;
  protected $debugOptionsDataType = '';
  public $experiments;
  public $flexResourceSchedulingGoal;
  public $internalExperiments;
  public $sdkPipelineOptions;
  public $serviceAccountEmail;
  public $serviceKmsKeyName;
  public $serviceOptions;
  public $shuffleMode;
  public $tempStoragePrefix;
  public $userAgent;
  public $version;
  protected $workerPoolsType = WorkerPool::class;
  protected $workerPoolsDataType = 'array';
  public $workerRegion;
  public $workerZone;

  public function setClusterManagerApiService($clusterManagerApiService)
  {
    $this->clusterManagerApiService = $clusterManagerApiService;
  }
  public function getClusterManagerApiService()
  {
    return $this->clusterManagerApiService;
  }
  public function setDataset($dataset)
  {
    $this->dataset = $dataset;
  }
  public function getDataset()
  {
    return $this->dataset;
  }
  /**
   * @param DebugOptions
   */
  public function setDebugOptions(DebugOptions $debugOptions)
  {
    $this->debugOptions = $debugOptions;
  }
  /**
   * @return DebugOptions
   */
  public function getDebugOptions()
  {
    return $this->debugOptions;
  }
  public function setExperiments($experiments)
  {
    $this->experiments = $experiments;
  }
  public function getExperiments()
  {
    return $this->experiments;
  }
  public function setFlexResourceSchedulingGoal($flexResourceSchedulingGoal)
  {
    $this->flexResourceSchedulingGoal = $flexResourceSchedulingGoal;
  }
  public function getFlexResourceSchedulingGoal()
  {
    return $this->flexResourceSchedulingGoal;
  }
  public function setInternalExperiments($internalExperiments)
  {
    $this->internalExperiments = $internalExperiments;
  }
  public function getInternalExperiments()
  {
    return $this->internalExperiments;
  }
  public function setSdkPipelineOptions($sdkPipelineOptions)
  {
    $this->sdkPipelineOptions = $sdkPipelineOptions;
  }
  public function getSdkPipelineOptions()
  {
    return $this->sdkPipelineOptions;
  }
  public function setServiceAccountEmail($serviceAccountEmail)
  {
    $this->serviceAccountEmail = $serviceAccountEmail;
  }
  public function getServiceAccountEmail()
  {
    return $this->serviceAccountEmail;
  }
  public function setServiceKmsKeyName($serviceKmsKeyName)
  {
    $this->serviceKmsKeyName = $serviceKmsKeyName;
  }
  public function getServiceKmsKeyName()
  {
    return $this->serviceKmsKeyName;
  }
  public function setServiceOptions($serviceOptions)
  {
    $this->serviceOptions = $serviceOptions;
  }
  public function getServiceOptions()
  {
    return $this->serviceOptions;
  }
  public function setShuffleMode($shuffleMode)
  {
    $this->shuffleMode = $shuffleMode;
  }
  public function getShuffleMode()
  {
    return $this->shuffleMode;
  }
  public function setTempStoragePrefix($tempStoragePrefix)
  {
    $this->tempStoragePrefix = $tempStoragePrefix;
  }
  public function getTempStoragePrefix()
  {
    return $this->tempStoragePrefix;
  }
  public function setUserAgent($userAgent)
  {
    $this->userAgent = $userAgent;
  }
  public function getUserAgent()
  {
    return $this->userAgent;
  }
  public function setVersion($version)
  {
    $this->version = $version;
  }
  public function getVersion()
  {
    return $this->version;
  }
  /**
   * @param WorkerPool[]
   */
  public function setWorkerPools($workerPools)
  {
    $this->workerPools = $workerPools;
  }
  /**
   * @return WorkerPool[]
   */
  public function getWorkerPools()
  {
    return $this->workerPools;
  }
  public function setWorkerRegion($workerRegion)
  {
    $this->workerRegion = $workerRegion;
  }
  public function getWorkerRegion()
  {
    return $this->workerRegion;
  }
  public function setWorkerZone($workerZone)
  {
    $this->workerZone = $workerZone;
  }
  public function getWorkerZone()
  {
    return $this->workerZone;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Environment::class, 'Google_Service_Dataflow_Environment');
