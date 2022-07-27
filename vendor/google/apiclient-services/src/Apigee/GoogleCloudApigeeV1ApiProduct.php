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

namespace Google\Service\Apigee;

class GoogleCloudApigeeV1ApiProduct extends \Google\Collection
{
  protected $collection_key = 'scopes';
  public $apiResources;
  public $approvalType;
  protected $attributesType = GoogleCloudApigeeV1Attribute::class;
  protected $attributesDataType = 'array';
  public $createdAt;
  public $description;
  public $displayName;
  public $environments;
  protected $graphqlOperationGroupType = GoogleCloudApigeeV1GraphQLOperationGroup::class;
  protected $graphqlOperationGroupDataType = '';
  public $lastModifiedAt;
  public $name;
  protected $operationGroupType = GoogleCloudApigeeV1OperationGroup::class;
  protected $operationGroupDataType = '';
  public $proxies;
  public $quota;
  public $quotaInterval;
  public $quotaTimeUnit;
  public $scopes;

  public function setApiResources($apiResources)
  {
    $this->apiResources = $apiResources;
  }
  public function getApiResources()
  {
    return $this->apiResources;
  }
  public function setApprovalType($approvalType)
  {
    $this->approvalType = $approvalType;
  }
  public function getApprovalType()
  {
    return $this->approvalType;
  }
  /**
   * @param GoogleCloudApigeeV1Attribute[]
   */
  public function setAttributes($attributes)
  {
    $this->attributes = $attributes;
  }
  /**
   * @return GoogleCloudApigeeV1Attribute[]
   */
  public function getAttributes()
  {
    return $this->attributes;
  }
  public function setCreatedAt($createdAt)
  {
    $this->createdAt = $createdAt;
  }
  public function getCreatedAt()
  {
    return $this->createdAt;
  }
  public function setDescription($description)
  {
    $this->description = $description;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  public function getDisplayName()
  {
    return $this->displayName;
  }
  public function setEnvironments($environments)
  {
    $this->environments = $environments;
  }
  public function getEnvironments()
  {
    return $this->environments;
  }
  /**
   * @param GoogleCloudApigeeV1GraphQLOperationGroup
   */
  public function setGraphqlOperationGroup(GoogleCloudApigeeV1GraphQLOperationGroup $graphqlOperationGroup)
  {
    $this->graphqlOperationGroup = $graphqlOperationGroup;
  }
  /**
   * @return GoogleCloudApigeeV1GraphQLOperationGroup
   */
  public function getGraphqlOperationGroup()
  {
    return $this->graphqlOperationGroup;
  }
  public function setLastModifiedAt($lastModifiedAt)
  {
    $this->lastModifiedAt = $lastModifiedAt;
  }
  public function getLastModifiedAt()
  {
    return $this->lastModifiedAt;
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
   * @param GoogleCloudApigeeV1OperationGroup
   */
  public function setOperationGroup(GoogleCloudApigeeV1OperationGroup $operationGroup)
  {
    $this->operationGroup = $operationGroup;
  }
  /**
   * @return GoogleCloudApigeeV1OperationGroup
   */
  public function getOperationGroup()
  {
    return $this->operationGroup;
  }
  public function setProxies($proxies)
  {
    $this->proxies = $proxies;
  }
  public function getProxies()
  {
    return $this->proxies;
  }
  public function setQuota($quota)
  {
    $this->quota = $quota;
  }
  public function getQuota()
  {
    return $this->quota;
  }
  public function setQuotaInterval($quotaInterval)
  {
    $this->quotaInterval = $quotaInterval;
  }
  public function getQuotaInterval()
  {
    return $this->quotaInterval;
  }
  public function setQuotaTimeUnit($quotaTimeUnit)
  {
    $this->quotaTimeUnit = $quotaTimeUnit;
  }
  public function getQuotaTimeUnit()
  {
    return $this->quotaTimeUnit;
  }
  public function setScopes($scopes)
  {
    $this->scopes = $scopes;
  }
  public function getScopes()
  {
    return $this->scopes;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudApigeeV1ApiProduct::class, 'Google_Service_Apigee_GoogleCloudApigeeV1ApiProduct');
