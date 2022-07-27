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

namespace Google\Service\CloudBuild;

class BuildOptions extends \Google\Collection
{
  protected $collection_key = 'volumes';
  public $diskSizeGb;
  public $dynamicSubstitutions;
  public $env;
  public $logStreamingOption;
  public $logging;
  public $machineType;
  protected $poolType = PoolOption::class;
  protected $poolDataType = '';
  public $requestedVerifyOption;
  public $secretEnv;
  public $sourceProvenanceHash;
  public $substitutionOption;
  protected $volumesType = Volume::class;
  protected $volumesDataType = 'array';
  public $workerPool;

  public function setDiskSizeGb($diskSizeGb)
  {
    $this->diskSizeGb = $diskSizeGb;
  }
  public function getDiskSizeGb()
  {
    return $this->diskSizeGb;
  }
  public function setDynamicSubstitutions($dynamicSubstitutions)
  {
    $this->dynamicSubstitutions = $dynamicSubstitutions;
  }
  public function getDynamicSubstitutions()
  {
    return $this->dynamicSubstitutions;
  }
  public function setEnv($env)
  {
    $this->env = $env;
  }
  public function getEnv()
  {
    return $this->env;
  }
  public function setLogStreamingOption($logStreamingOption)
  {
    $this->logStreamingOption = $logStreamingOption;
  }
  public function getLogStreamingOption()
  {
    return $this->logStreamingOption;
  }
  public function setLogging($logging)
  {
    $this->logging = $logging;
  }
  public function getLogging()
  {
    return $this->logging;
  }
  public function setMachineType($machineType)
  {
    $this->machineType = $machineType;
  }
  public function getMachineType()
  {
    return $this->machineType;
  }
  /**
   * @param PoolOption
   */
  public function setPool(PoolOption $pool)
  {
    $this->pool = $pool;
  }
  /**
   * @return PoolOption
   */
  public function getPool()
  {
    return $this->pool;
  }
  public function setRequestedVerifyOption($requestedVerifyOption)
  {
    $this->requestedVerifyOption = $requestedVerifyOption;
  }
  public function getRequestedVerifyOption()
  {
    return $this->requestedVerifyOption;
  }
  public function setSecretEnv($secretEnv)
  {
    $this->secretEnv = $secretEnv;
  }
  public function getSecretEnv()
  {
    return $this->secretEnv;
  }
  public function setSourceProvenanceHash($sourceProvenanceHash)
  {
    $this->sourceProvenanceHash = $sourceProvenanceHash;
  }
  public function getSourceProvenanceHash()
  {
    return $this->sourceProvenanceHash;
  }
  public function setSubstitutionOption($substitutionOption)
  {
    $this->substitutionOption = $substitutionOption;
  }
  public function getSubstitutionOption()
  {
    return $this->substitutionOption;
  }
  /**
   * @param Volume[]
   */
  public function setVolumes($volumes)
  {
    $this->volumes = $volumes;
  }
  /**
   * @return Volume[]
   */
  public function getVolumes()
  {
    return $this->volumes;
  }
  public function setWorkerPool($workerPool)
  {
    $this->workerPool = $workerPool;
  }
  public function getWorkerPool()
  {
    return $this->workerPool;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BuildOptions::class, 'Google_Service_CloudBuild_BuildOptions');
