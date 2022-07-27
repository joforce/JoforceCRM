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

class Snapshot extends \Google\Collection
{
  protected $collection_key = 'storageLocations';
  public $autoCreated;
  public $chainName;
  public $creationTimestamp;
  public $description;
  public $diskSizeGb;
  public $downloadBytes;
  public $id;
  public $kind;
  public $labelFingerprint;
  public $labels;
  public $licenseCodes;
  public $licenses;
  public $locationHint;
  public $name;
  public $satisfiesPzs;
  public $selfLink;
  protected $snapshotEncryptionKeyType = CustomerEncryptionKey::class;
  protected $snapshotEncryptionKeyDataType = '';
  public $sourceDisk;
  protected $sourceDiskEncryptionKeyType = CustomerEncryptionKey::class;
  protected $sourceDiskEncryptionKeyDataType = '';
  public $sourceDiskId;
  public $status;
  public $storageBytes;
  public $storageBytesStatus;
  public $storageLocations;

  public function setAutoCreated($autoCreated)
  {
    $this->autoCreated = $autoCreated;
  }
  public function getAutoCreated()
  {
    return $this->autoCreated;
  }
  public function setChainName($chainName)
  {
    $this->chainName = $chainName;
  }
  public function getChainName()
  {
    return $this->chainName;
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
  public function setDiskSizeGb($diskSizeGb)
  {
    $this->diskSizeGb = $diskSizeGb;
  }
  public function getDiskSizeGb()
  {
    return $this->diskSizeGb;
  }
  public function setDownloadBytes($downloadBytes)
  {
    $this->downloadBytes = $downloadBytes;
  }
  public function getDownloadBytes()
  {
    return $this->downloadBytes;
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
  public function setLabelFingerprint($labelFingerprint)
  {
    $this->labelFingerprint = $labelFingerprint;
  }
  public function getLabelFingerprint()
  {
    return $this->labelFingerprint;
  }
  public function setLabels($labels)
  {
    $this->labels = $labels;
  }
  public function getLabels()
  {
    return $this->labels;
  }
  public function setLicenseCodes($licenseCodes)
  {
    $this->licenseCodes = $licenseCodes;
  }
  public function getLicenseCodes()
  {
    return $this->licenseCodes;
  }
  public function setLicenses($licenses)
  {
    $this->licenses = $licenses;
  }
  public function getLicenses()
  {
    return $this->licenses;
  }
  public function setLocationHint($locationHint)
  {
    $this->locationHint = $locationHint;
  }
  public function getLocationHint()
  {
    return $this->locationHint;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setSatisfiesPzs($satisfiesPzs)
  {
    $this->satisfiesPzs = $satisfiesPzs;
  }
  public function getSatisfiesPzs()
  {
    return $this->satisfiesPzs;
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
   * @param CustomerEncryptionKey
   */
  public function setSnapshotEncryptionKey(CustomerEncryptionKey $snapshotEncryptionKey)
  {
    $this->snapshotEncryptionKey = $snapshotEncryptionKey;
  }
  /**
   * @return CustomerEncryptionKey
   */
  public function getSnapshotEncryptionKey()
  {
    return $this->snapshotEncryptionKey;
  }
  public function setSourceDisk($sourceDisk)
  {
    $this->sourceDisk = $sourceDisk;
  }
  public function getSourceDisk()
  {
    return $this->sourceDisk;
  }
  /**
   * @param CustomerEncryptionKey
   */
  public function setSourceDiskEncryptionKey(CustomerEncryptionKey $sourceDiskEncryptionKey)
  {
    $this->sourceDiskEncryptionKey = $sourceDiskEncryptionKey;
  }
  /**
   * @return CustomerEncryptionKey
   */
  public function getSourceDiskEncryptionKey()
  {
    return $this->sourceDiskEncryptionKey;
  }
  public function setSourceDiskId($sourceDiskId)
  {
    $this->sourceDiskId = $sourceDiskId;
  }
  public function getSourceDiskId()
  {
    return $this->sourceDiskId;
  }
  public function setStatus($status)
  {
    $this->status = $status;
  }
  public function getStatus()
  {
    return $this->status;
  }
  public function setStorageBytes($storageBytes)
  {
    $this->storageBytes = $storageBytes;
  }
  public function getStorageBytes()
  {
    return $this->storageBytes;
  }
  public function setStorageBytesStatus($storageBytesStatus)
  {
    $this->storageBytesStatus = $storageBytesStatus;
  }
  public function getStorageBytesStatus()
  {
    return $this->storageBytesStatus;
  }
  public function setStorageLocations($storageLocations)
  {
    $this->storageLocations = $storageLocations;
  }
  public function getStorageLocations()
  {
    return $this->storageLocations;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Snapshot::class, 'Google_Service_Compute_Snapshot');
