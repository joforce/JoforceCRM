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

namespace Google\Service\Compute;

class SslCertificate extends \Google\Collection
{
  protected $collection_key = 'subjectAlternativeNames';
  public $certificate;
  public $creationTimestamp;
  public $description;
  public $expireTime;
  public $id;
  public $kind;
  protected $managedType = SslCertificateManagedSslCertificate::class;
  protected $managedDataType = '';
  public $name;
  public $privateKey;
  public $region;
  public $selfLink;
  protected $selfManagedType = SslCertificateSelfManagedSslCertificate::class;
  protected $selfManagedDataType = '';
  public $subjectAlternativeNames;
  public $type;

  public function setCertificate($certificate)
  {
    $this->certificate = $certificate;
  }
  public function getCertificate()
  {
    return $this->certificate;
  }
  public function setCreationTimestamp($creationTimestamp)
  {
    $this->creationTimestamp = $creationTimestamp;
  }
  public function getCreationTimestamp()
  {
    return $this->creationTimestamp;
  }
  public function setDescription($description)
  {
    $this->description = $description;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function setExpireTime($expireTime)
  {
    $this->expireTime = $expireTime;
  }
  public function getExpireTime()
  {
    return $this->expireTime;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param SslCertificateManagedSslCertificate
   */
  public function setManaged(SslCertificateManagedSslCertificate $managed)
  {
    $this->managed = $managed;
  }
  /**
   * @return SslCertificateManagedSslCertificate
   */
  public function getManaged()
  {
    return $this->managed;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setPrivateKey($privateKey)
  {
    $this->privateKey = $privateKey;
  }
  public function getPrivateKey()
  {
    return $this->privateKey;
  }
  public function setRegion($region)
  {
    $this->region = $region;
  }
  public function getRegion()
  {
    return $this->region;
  }
  public function setSelfLink($selfLink)
  {
    $this->selfLink = $selfLink;
  }
  public function getSelfLink()
  {
    return $this->selfLink;
  }
  /**
   * @param SslCertificateSelfManagedSslCertificate
   */
  public function setSelfManaged(SslCertificateSelfManagedSslCertificate $selfManaged)
  {
    $this->selfManaged = $selfManaged;
  }
  /**
   * @return SslCertificateSelfManagedSslCertificate
   */
  public function getSelfManaged()
  {
    return $this->selfManaged;
  }
  public function setSubjectAlternativeNames($subjectAlternativeNames)
  {
    $this->subjectAlternativeNames = $subjectAlternativeNames;
  }
  public function getSubjectAlternativeNames()
  {
    return $this->subjectAlternativeNames;
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
class_alias(SslCertificate::class, 'Google_Service_Compute_SslCertificate');
