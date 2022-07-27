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

namespace Google\Service\AndroidManagement;

class ApplicationPolicy extends \Google\Collection
{
  protected $collection_key = 'permissionGrants';
  public $accessibleTrackIds;
  public $autoUpdateMode;
  public $connectedWorkAndPersonalApp;
  public $defaultPermissionPolicy;
  public $delegatedScopes;
  public $disabled;
  protected $extensionConfigType = ExtensionConfig::class;
  protected $extensionConfigDataType = '';
  public $installType;
  public $lockTaskAllowed;
  public $managedConfiguration;
  protected $managedConfigurationTemplateType = ManagedConfigurationTemplate::class;
  protected $managedConfigurationTemplateDataType = '';
  public $minimumVersionCode;
  public $packageName;
  protected $permissionGrantsType = PermissionGrant::class;
  protected $permissionGrantsDataType = 'array';

  public function setAccessibleTrackIds($accessibleTrackIds)
  {
    $this->accessibleTrackIds = $accessibleTrackIds;
  }
  public function getAccessibleTrackIds()
  {
    return $this->accessibleTrackIds;
  }
  public function setAutoUpdateMode($autoUpdateMode)
  {
    $this->autoUpdateMode = $autoUpdateMode;
  }
  public function getAutoUpdateMode()
  {
    return $this->autoUpdateMode;
  }
  public function setConnectedWorkAndPersonalApp($connectedWorkAndPersonalApp)
  {
    $this->connectedWorkAndPersonalApp = $connectedWorkAndPersonalApp;
  }
  public function getConnectedWorkAndPersonalApp()
  {
    return $this->connectedWorkAndPersonalApp;
  }
  public function setDefaultPermissionPolicy($defaultPermissionPolicy)
  {
    $this->defaultPermissionPolicy = $defaultPermissionPolicy;
  }
  public function getDefaultPermissionPolicy()
  {
    return $this->defaultPermissionPolicy;
  }
  public function setDelegatedScopes($delegatedScopes)
  {
    $this->delegatedScopes = $delegatedScopes;
  }
  public function getDelegatedScopes()
  {
    return $this->delegatedScopes;
  }
  public function setDisabled($disabled)
  {
    $this->disabled = $disabled;
  }
  public function getDisabled()
  {
    return $this->disabled;
  }
  /**
   * @param ExtensionConfig
   */
  public function setExtensionConfig(ExtensionConfig $extensionConfig)
  {
    $this->extensionConfig = $extensionConfig;
  }
  /**
   * @return ExtensionConfig
   */
  public function getExtensionConfig()
  {
    return $this->extensionConfig;
  }
  public function setInstallType($installType)
  {
    $this->installType = $installType;
  }
  public function getInstallType()
  {
    return $this->installType;
  }
  public function setLockTaskAllowed($lockTaskAllowed)
  {
    $this->lockTaskAllowed = $lockTaskAllowed;
  }
  public function getLockTaskAllowed()
  {
    return $this->lockTaskAllowed;
  }
  public function setManagedConfiguration($managedConfiguration)
  {
    $this->managedConfiguration = $managedConfiguration;
  }
  public function getManagedConfiguration()
  {
    return $this->managedConfiguration;
  }
  /**
   * @param ManagedConfigurationTemplate
   */
  public function setManagedConfigurationTemplate(ManagedConfigurationTemplate $managedConfigurationTemplate)
  {
    $this->managedConfigurationTemplate = $managedConfigurationTemplate;
  }
  /**
   * @return ManagedConfigurationTemplate
   */
  public function getManagedConfigurationTemplate()
  {
    return $this->managedConfigurationTemplate;
  }
  public function setMinimumVersionCode($minimumVersionCode)
  {
    $this->minimumVersionCode = $minimumVersionCode;
  }
  public function getMinimumVersionCode()
  {
    return $this->minimumVersionCode;
  }
  public function setPackageName($packageName)
  {
    $this->packageName = $packageName;
  }
  public function getPackageName()
  {
    return $this->packageName;
  }
  /**
   * @param PermissionGrant[]
   */
  public function setPermissionGrants($permissionGrants)
  {
    $this->permissionGrants = $permissionGrants;
  }
  /**
   * @return PermissionGrant[]
   */
  public function getPermissionGrants()
  {
    return $this->permissionGrants;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ApplicationPolicy::class, 'Google_Service_AndroidManagement_ApplicationPolicy');
