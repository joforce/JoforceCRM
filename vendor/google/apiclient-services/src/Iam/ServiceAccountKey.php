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

namespace Google\Service\Iam;

class ServiceAccountKey extends \Google\Model
{
  public $disabled;
  public $keyAlgorithm;
  public $keyOrigin;
  public $keyType;
  public $name;
  public $privateKeyData;
  public $privateKeyType;
  public $publicKeyData;
  public $validAfterTime;
  public $validBeforeTime;

  public function setDisabled($disabled)
  {
    $this->disabled = $disabled;
  }
  public function getDisabled()
  {
    return $this->disabled;
  }
  public function setKeyAlgorithm($keyAlgorithm)
  {
    $this->keyAlgorithm = $keyAlgorithm;
  }
  public function getKeyAlgorithm()
  {
    return $this->keyAlgorithm;
  }
  public function setKeyOrigin($keyOrigin)
  {
    $this->keyOrigin = $keyOrigin;
  }
  public function getKeyOrigin()
  {
    return $this->keyOrigin;
  }
  public function setKeyType($keyType)
  {
    $this->keyType = $keyType;
  }
  public function getKeyType()
  {
    return $this->keyType;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setPrivateKeyData($privateKeyData)
  {
    $this->privateKeyData = $privateKeyData;
  }
  public function getPrivateKeyData()
  {
    return $this->privateKeyData;
  }
  public function setPrivateKeyType($privateKeyType)
  {
    $this->privateKeyType = $privateKeyType;
  }
  public function getPrivateKeyType()
  {
    return $this->privateKeyType;
  }
  public function setPublicKeyData($publicKeyData)
  {
    $this->publicKeyData = $publicKeyData;
  }
  public function getPublicKeyData()
  {
    return $this->publicKeyData;
  }
  public function setValidAfterTime($validAfterTime)
  {
    $this->validAfterTime = $validAfterTime;
  }
  public function getValidAfterTime()
  {
    return $this->validAfterTime;
  }
  public function setValidBeforeTime($validBeforeTime)
  {
    $this->validBeforeTime = $validBeforeTime;
  }
  public function getValidBeforeTime()
  {
    return $this->validBeforeTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ServiceAccountKey::class, 'Google_Service_Iam_ServiceAccountKey');
