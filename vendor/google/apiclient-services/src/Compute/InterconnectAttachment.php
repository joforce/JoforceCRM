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

class InterconnectAttachment extends \Google\Collection
{
  protected $collection_key = 'ipsecInternalAddresses';
  public $adminEnabled;
  public $bandwidth;
  public $candidateSubnets;
  public $cloudRouterIpAddress;
  public $creationTimestamp;
  public $customerRouterIpAddress;
  public $dataplaneVersion;
  public $description;
  public $edgeAvailabilityDomain;
  public $encryption;
  public $googleReferenceId;
  public $id;
  public $interconnect;
  public $ipsecInternalAddresses;
  public $kind;
  public $mtu;
  public $name;
  public $operationalStatus;
  public $pairingKey;
  public $partnerAsn;
  protected $partnerMetadataType = InterconnectAttachmentPartnerMetadata::class;
  protected $partnerMetadataDataType = '';
  protected $privateInterconnectInfoType = InterconnectAttachmentPrivateInfo::class;
  protected $privateInterconnectInfoDataType = '';
  public $region;
  public $router;
  public $satisfiesPzs;
  public $selfLink;
  public $state;
  public $type;
  public $vlanTag8021q;

  public function setAdminEnabled($adminEnabled)
  {
    $this->adminEnabled = $adminEnabled;
  }
  public function getAdminEnabled()
  {
    return $this->adminEnabled;
  }
  public function setBandwidth($bandwidth)
  {
    $this->bandwidth = $bandwidth;
  }
  public function getBandwidth()
  {
    return $this->bandwidth;
  }
  public function setCandidateSubnets($candidateSubnets)
  {
    $this->candidateSubnets = $candidateSubnets;
  }
  public function getCandidateSubnets()
  {
    return $this->candidateSubnets;
  }
  public function setCloudRouterIpAddress($cloudRouterIpAddress)
  {
    $this->cloudRouterIpAddress = $cloudRouterIpAddress;
  }
  public function getCloudRouterIpAddress()
  {
    return $this->cloudRouterIpAddress;
  }
  public function setCreationTimestamp($creationTimestamp)
  {
    $this->creationTimestamp = $creationTimestamp;
  }
  public function getCreationTimestamp()
  {
    return $this->creationTimestamp;
  }
  public function setCustomerRouterIpAddress($customerRouterIpAddress)
  {
    $this->customerRouterIpAddress = $customerRouterIpAddress;
  }
  public function getCustomerRouterIpAddress()
  {
    return $this->customerRouterIpAddress;
  }
  public function setDataplaneVersion($dataplaneVersion)
  {
    $this->dataplaneVersion = $dataplaneVersion;
  }
  public function getDataplaneVersion()
  {
    return $this->dataplaneVersion;
  }
  public function setDescription($description)
  {
    $this->description = $description;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function setEdgeAvailabilityDomain($edgeAvailabilityDomain)
  {
    $this->edgeAvailabilityDomain = $edgeAvailabilityDomain;
  }
  public function getEdgeAvailabilityDomain()
  {
    return $this->edgeAvailabilityDomain;
  }
  public function setEncryption($encryption)
  {
    $this->encryption = $encryption;
  }
  public function getEncryption()
  {
    return $this->encryption;
  }
  public function setGoogleReferenceId($googleReferenceId)
  {
    $this->googleReferenceId = $googleReferenceId;
  }
  public function getGoogleReferenceId()
  {
    return $this->googleReferenceId;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setInterconnect($interconnect)
  {
    $this->interconnect = $interconnect;
  }
  public function getInterconnect()
  {
    return $this->interconnect;
  }
  public function setIpsecInternalAddresses($ipsecInternalAddresses)
  {
    $this->ipsecInternalAddresses = $ipsecInternalAddresses;
  }
  public function getIpsecInternalAddresses()
  {
    return $this->ipsecInternalAddresses;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setMtu($mtu)
  {
    $this->mtu = $mtu;
  }
  public function getMtu()
  {
    return $this->mtu;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setOperationalStatus($operationalStatus)
  {
    $this->operationalStatus = $operationalStatus;
  }
  public function getOperationalStatus()
  {
    return $this->operationalStatus;
  }
  public function setPairingKey($pairingKey)
  {
    $this->pairingKey = $pairingKey;
  }
  public function getPairingKey()
  {
    return $this->pairingKey;
  }
  public function setPartnerAsn($partnerAsn)
  {
    $this->partnerAsn = $partnerAsn;
  }
  public function getPartnerAsn()
  {
    return $this->partnerAsn;
  }
  /**
   * @param InterconnectAttachmentPartnerMetadata
   */
  public function setPartnerMetadata(InterconnectAttachmentPartnerMetadata $partnerMetadata)
  {
    $this->partnerMetadata = $partnerMetadata;
  }
  /**
   * @return InterconnectAttachmentPartnerMetadata
   */
  public function getPartnerMetadata()
  {
    return $this->partnerMetadata;
  }
  /**
   * @param InterconnectAttachmentPrivateInfo
   */
  public function setPrivateInterconnectInfo(InterconnectAttachmentPrivateInfo $privateInterconnectInfo)
  {
    $this->privateInterconnectInfo = $privateInterconnectInfo;
  }
  /**
   * @return InterconnectAttachmentPrivateInfo
   */
  public function getPrivateInterconnectInfo()
  {
    return $this->privateInterconnectInfo;
  }
  public function setRegion($region)
  {
    $this->region = $region;
  }
  public function getRegion()
  {
    return $this->region;
  }
  public function setRouter($router)
  {
    $this->router = $router;
  }
  public function getRouter()
  {
    return $this->router;
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
  public function setState($state)
  {
    $this->state = $state;
  }
  public function getState()
  {
    return $this->state;
  }
  public function setType($type)
  {
    $this->type = $type;
  }
  public function getType()
  {
    return $this->type;
  }
  public function setVlanTag8021q($vlanTag8021q)
  {
    $this->vlanTag8021q = $vlanTag8021q;
  }
  public function getVlanTag8021q()
  {
    return $this->vlanTag8021q;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(InterconnectAttachment::class, 'Google_Service_Compute_InterconnectAttachment');
