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

namespace Google\Service\SQLAdmin;

class User extends \Google\Model
{
  public $etag;
  public $host;
  public $instance;
  public $kind;
  public $name;
  public $password;
  public $project;
  protected $sqlserverUserDetailsType = SqlServerUserDetails::class;
  protected $sqlserverUserDetailsDataType = '';
  public $type;

  public function setEtag($etag)
  {
    $this->etag = $etag;
  }
  public function getEtag()
  {
    return $this->etag;
  }
  public function setHost($host)
  {
    $this->host = $host;
  }
  public function getHost()
  {
    return $this->host;
  }
  public function setInstance($instance)
  {
    $this->instance = $instance;
  }
  public function getInstance()
  {
    return $this->instance;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setPassword($password)
  {
    $this->password = $password;
  }
  public function getPassword()
  {
    return $this->password;
  }
  public function setProject($project)
  {
    $this->project = $project;
  }
  public function getProject()
  {
    return $this->project;
  }
  /**
   * @param SqlServerUserDetails
   */
  public function setSqlserverUserDetails(SqlServerUserDetails $sqlserverUserDetails)
  {
    $this->sqlserverUserDetails = $sqlserverUserDetails;
  }
  /**
   * @return SqlServerUserDetails
   */
  public function getSqlserverUserDetails()
  {
    return $this->sqlserverUserDetails;
  }
  public function setType($type)
  {
    $this->type = $type;
  }
  public function getType()
  {
    return $this->type;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(User::class, 'Google_Service_SQLAdmin_User');
