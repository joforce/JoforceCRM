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

namespace Google\Service\ShoppingContent;

class OrderTrackingSignalShippingInfo extends \Google\Model
{
  protected $actualDeliveryTimeType = DateTime::class;
  protected $actualDeliveryTimeDataType = '';
  public $carrierName;
  public $carrierServiceName;
  protected $earliestDeliveryPromiseTimeType = DateTime::class;
  protected $earliestDeliveryPromiseTimeDataType = '';
  protected $latestDeliveryPromiseTimeType = DateTime::class;
  protected $latestDeliveryPromiseTimeDataType = '';
  public $originPostalCode;
  public $originRegionCode;
  public $shipmentId;
  protected $shippedTimeType = DateTime::class;
  protected $shippedTimeDataType = '';
  public $shippingStatus;
  public $trackingId;

  /**
   * @param DateTime
   */
  public function setActualDeliveryTime(DateTime $actualDeliveryTime)
  {
    $this->actualDeliveryTime = $actualDeliveryTime;
  }
  /**
   * @return DateTime
   */
  public function getActualDeliveryTime()
  {
    return $this->actualDeliveryTime;
  }
  public function setCarrierName($carrierName)
  {
    $this->carrierName = $carrierName;
  }
  public function getCarrierName()
  {
    return $this->carrierName;
  }
  public function setCarrierServiceName($carrierServiceName)
  {
    $this->carrierServiceName = $carrierServiceName;
  }
  public function getCarrierServiceName()
  {
    return $this->carrierServiceName;
  }
  /**
   * @param DateTime
   */
  public function setEarliestDeliveryPromiseTime(DateTime $earliestDeliveryPromiseTime)
  {
    $this->earliestDeliveryPromiseTime = $earliestDeliveryPromiseTime;
  }
  /**
   * @return DateTime
   */
  public function getEarliestDeliveryPromiseTime()
  {
    return $this->earliestDeliveryPromiseTime;
  }
  /**
   * @param DateTime
   */
  public function setLatestDeliveryPromiseTime(DateTime $latestDeliveryPromiseTime)
  {
    $this->latestDeliveryPromiseTime = $latestDeliveryPromiseTime;
  }
  /**
   * @return DateTime
   */
  public function getLatestDeliveryPromiseTime()
  {
    return $this->latestDeliveryPromiseTime;
  }
  public function setOriginPostalCode($originPostalCode)
  {
    $this->originPostalCode = $originPostalCode;
  }
  public function getOriginPostalCode()
  {
    return $this->originPostalCode;
  }
  public function setOriginRegionCode($originRegionCode)
  {
    $this->originRegionCode = $originRegionCode;
  }
  public function getOriginRegionCode()
  {
    return $this->originRegionCode;
  }
  public function setShipmentId($shipmentId)
  {
    $this->shipmentId = $shipmentId;
  }
  public function getShipmentId()
  {
    return $this->shipmentId;
  }
  /**
   * @param DateTime
   */
  public function setShippedTime(DateTime $shippedTime)
  {
    $this->shippedTime = $shippedTime;
  }
  /**
   * @return DateTime
   */
  public function getShippedTime()
  {
    return $this->shippedTime;
  }
  public function setShippingStatus($shippingStatus)
  {
    $this->shippingStatus = $shippingStatus;
  }
  public function getShippingStatus()
  {
    return $this->shippingStatus;
  }
  public function setTrackingId($trackingId)
  {
    $this->trackingId = $trackingId;
  }
  public function getTrackingId()
  {
    return $this->trackingId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OrderTrackingSignalShippingInfo::class, 'Google_Service_ShoppingContent_OrderTrackingSignalShippingInfo');
