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

namespace Google\Service\CloudIdentity;

class GoogleAppsCloudidentityDevicesV1LookupSelfDeviceUsersResponse extends \Google\Collection
{
  protected $collection_key = 'names';
  public $customer;
  public $names;
  public $nextPageToken;

  public function setCustomer($customer)
  {
    $this->customer = $customer;
  }
  public function getCustomer()
  {
    return $this->customer;
  }
  public function setNames($names)
  {
    $this->names = $names;
  }
  public function getNames()
  {
    return $this->names;
  }
  public function setNextPageToken($nextPageToken)
  {
    $this->nextPageToken = $nextPageToken;
  }
  public function getNextPageToken()
  {
    return $this->nextPageToken;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleAppsCloudidentityDevicesV1LookupSelfDeviceUsersResponse::class, 'Google_Service_CloudIdentity_GoogleAppsCloudidentityDevicesV1LookupSelfDeviceUsersResponse');
