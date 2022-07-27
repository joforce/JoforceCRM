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

namespace Google\Service\CloudHealthcare;

class Message extends \Google\Collection
{
  protected $collection_key = 'patientIds';
  public $createTime;
  public $data;
  public $labels;
  public $messageType;
  public $name;
  protected $parsedDataType = ParsedData::class;
  protected $parsedDataDataType = '';
  protected $patientIdsType = PatientId::class;
  protected $patientIdsDataType = 'array';
  protected $schematizedDataType = SchematizedData::class;
  protected $schematizedDataDataType = '';
  public $sendFacility;
  public $sendTime;

  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  public function getCreateTime()
  {
    return $this->createTime;
  }
  public function setData($data)
  {
    $this->data = $data;
  }
  public function getData()
  {
    return $this->data;
  }
  public function setLabels($labels)
  {
    $this->labels = $labels;
  }
  public function getLabels()
  {
    return $this->labels;
  }
  public function setMessageType($messageType)
  {
    $this->messageType = $messageType;
  }
  public function getMessageType()
  {
    return $this->messageType;
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
   * @param ParsedData
   */
  public function setParsedData(ParsedData $parsedData)
  {
    $this->parsedData = $parsedData;
  }
  /**
   * @return ParsedData
   */
  public function getParsedData()
  {
    return $this->parsedData;
  }
  /**
   * @param PatientId[]
   */
  public function setPatientIds($patientIds)
  {
    $this->patientIds = $patientIds;
  }
  /**
   * @return PatientId[]
   */
  public function getPatientIds()
  {
    return $this->patientIds;
  }
  /**
   * @param SchematizedData
   */
  public function setSchematizedData(SchematizedData $schematizedData)
  {
    $this->schematizedData = $schematizedData;
  }
  /**
   * @return SchematizedData
   */
  public function getSchematizedData()
  {
    return $this->schematizedData;
  }
  public function setSendFacility($sendFacility)
  {
    $this->sendFacility = $sendFacility;
  }
  public function getSendFacility()
  {
    return $this->sendFacility;
  }
  public function setSendTime($sendTime)
  {
    $this->sendTime = $sendTime;
  }
  public function getSendTime()
  {
    return $this->sendTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Message::class, 'Google_Service_CloudHealthcare_Message');
