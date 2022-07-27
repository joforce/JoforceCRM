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

namespace Google\Service\DeploymentManager;

class Manifest extends \Google\Collection
{
  protected $collection_key = 'imports';
  protected $configType = ConfigFile::class;
  protected $configDataType = '';
  public $expandedConfig;
  public $id;
  protected $importsType = ImportFile::class;
  protected $importsDataType = 'array';
  public $insertTime;
  public $layout;
  public $manifestSizeBytes;
  public $manifestSizeLimitBytes;
  public $name;
  public $selfLink;

  /**
   * @param ConfigFile
   */
  public function setConfig(ConfigFile $config)
  {
    $this->config = $config;
  }
  /**
   * @return ConfigFile
   */
  public function getConfig()
  {
    return $this->config;
  }
  public function setExpandedConfig($expandedConfig)
  {
    $this->expandedConfig = $expandedConfig;
  }
  public function getExpandedConfig()
  {
    return $this->expandedConfig;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  /**
   * @param ImportFile[]
   */
  public function setImports($imports)
  {
    $this->imports = $imports;
  }
  /**
   * @return ImportFile[]
   */
  public function getImports()
  {
    return $this->imports;
  }
  public function setInsertTime($insertTime)
  {
    $this->insertTime = $insertTime;
  }
  public function getInsertTime()
  {
    return $this->insertTime;
  }
  public function setLayout($layout)
  {
    $this->layout = $layout;
  }
  public function getLayout()
  {
    return $this->layout;
  }
  public function setManifestSizeBytes($manifestSizeBytes)
  {
    $this->manifestSizeBytes = $manifestSizeBytes;
  }
  public function getManifestSizeBytes()
  {
    return $this->manifestSizeBytes;
  }
  public function setManifestSizeLimitBytes($manifestSizeLimitBytes)
  {
    $this->manifestSizeLimitBytes = $manifestSizeLimitBytes;
  }
  public function getManifestSizeLimitBytes()
  {
    return $this->manifestSizeLimitBytes;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setSelfLink($selfLink)
  {
    $this->selfLink = $selfLink;
  }
  public function getSelfLink()
  {
    return $this->selfLink;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Manifest::class, 'Google_Service_DeploymentManager_Manifest');
