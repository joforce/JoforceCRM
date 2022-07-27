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

namespace Google\Service\CloudLifeSciences;

class Action extends \Google\Collection
{
  protected $collection_key = 'mounts';
  public $alwaysRun;
  public $blockExternalNetwork;
  public $commands;
  public $containerName;
  protected $credentialsType = Secret::class;
  protected $credentialsDataType = '';
  public $disableImagePrefetch;
  public $disableStandardErrorCapture;
  public $enableFuse;
  protected $encryptedEnvironmentType = Secret::class;
  protected $encryptedEnvironmentDataType = '';
  public $entrypoint;
  public $environment;
  public $ignoreExitStatus;
  public $imageUri;
  public $labels;
  protected $mountsType = Mount::class;
  protected $mountsDataType = 'array';
  public $pidNamespace;
  public $portMappings;
  public $publishExposedPorts;
  public $runInBackground;
  public $timeout;

  public function setAlwaysRun($alwaysRun)
  {
    $this->alwaysRun = $alwaysRun;
  }
  public function getAlwaysRun()
  {
    return $this->alwaysRun;
  }
  public function setBlockExternalNetwork($blockExternalNetwork)
  {
    $this->blockExternalNetwork = $blockExternalNetwork;
  }
  public function getBlockExternalNetwork()
  {
    return $this->blockExternalNetwork;
  }
  public function setCommands($commands)
  {
    $this->commands = $commands;
  }
  public function getCommands()
  {
    return $this->commands;
  }
  public function setContainerName($containerName)
  {
    $this->containerName = $containerName;
  }
  public function getContainerName()
  {
    return $this->containerName;
  }
  /**
   * @param Secret
   */
  public function setCredentials(Secret $credentials)
  {
    $this->credentials = $credentials;
  }
  /**
   * @return Secret
   */
  public function getCredentials()
  {
    return $this->credentials;
  }
  public function setDisableImagePrefetch($disableImagePrefetch)
  {
    $this->disableImagePrefetch = $disableImagePrefetch;
  }
  public function getDisableImagePrefetch()
  {
    return $this->disableImagePrefetch;
  }
  public function setDisableStandardErrorCapture($disableStandardErrorCapture)
  {
    $this->disableStandardErrorCapture = $disableStandardErrorCapture;
  }
  public function getDisableStandardErrorCapture()
  {
    return $this->disableStandardErrorCapture;
  }
  public function setEnableFuse($enableFuse)
  {
    $this->enableFuse = $enableFuse;
  }
  public function getEnableFuse()
  {
    return $this->enableFuse;
  }
  /**
   * @param Secret
   */
  public function setEncryptedEnvironment(Secret $encryptedEnvironment)
  {
    $this->encryptedEnvironment = $encryptedEnvironment;
  }
  /**
   * @return Secret
   */
  public function getEncryptedEnvironment()
  {
    return $this->encryptedEnvironment;
  }
  public function setEntrypoint($entrypoint)
  {
    $this->entrypoint = $entrypoint;
  }
  public function getEntrypoint()
  {
    return $this->entrypoint;
  }
  public function setEnvironment($environment)
  {
    $this->environment = $environment;
  }
  public function getEnvironment()
  {
    return $this->environment;
  }
  public function setIgnoreExitStatus($ignoreExitStatus)
  {
    $this->ignoreExitStatus = $ignoreExitStatus;
  }
  public function getIgnoreExitStatus()
  {
    return $this->ignoreExitStatus;
  }
  public function setImageUri($imageUri)
  {
    $this->imageUri = $imageUri;
  }
  public function getImageUri()
  {
    return $this->imageUri;
  }
  public function setLabels($labels)
  {
    $this->labels = $labels;
  }
  public function getLabels()
  {
    return $this->labels;
  }
  /**
   * @param Mount[]
   */
  public function setMounts($mounts)
  {
    $this->mounts = $mounts;
  }
  /**
   * @return Mount[]
   */
  public function getMounts()
  {
    return $this->mounts;
  }
  public function setPidNamespace($pidNamespace)
  {
    $this->pidNamespace = $pidNamespace;
  }
  public function getPidNamespace()
  {
    return $this->pidNamespace;
  }
  public function setPortMappings($portMappings)
  {
    $this->portMappings = $portMappings;
  }
  public function getPortMappings()
  {
    return $this->portMappings;
  }
  public function setPublishExposedPorts($publishExposedPorts)
  {
    $this->publishExposedPorts = $publishExposedPorts;
  }
  public function getPublishExposedPorts()
  {
    return $this->publishExposedPorts;
  }
  public function setRunInBackground($runInBackground)
  {
    $this->runInBackground = $runInBackground;
  }
  public function getRunInBackground()
  {
    return $this->runInBackground;
  }
  public function setTimeout($timeout)
  {
    $this->timeout = $timeout;
  }
  public function getTimeout()
  {
    return $this->timeout;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Action::class, 'Google_Service_CloudLifeSciences_Action');
