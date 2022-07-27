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

class GoogleCloudApigeeV1AsyncQueryResult extends \Google\Model
{
  public $expires;
  public $self;

  public function setExpires($expires)
  {
    $this->expires = $expires;
  }
  public function getExpires()
  {
    return $this->expires;
  }
  public function setSelf($self)
  {
    $this->self = $self;
  }
  public function getSelf()
  {
    return $this->self;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudApigeeV1AsyncQueryResult::class, 'Google_Service_Apigee_GoogleCloudApigeeV1AsyncQueryResult');
