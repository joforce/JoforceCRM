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

namespace Google\Service\AnalyticsData;

class ResponseMetaData extends \Google\Model
{
  public $currencyCode;
  public $dataLossFromOtherRow;
  public $emptyReason;
  protected $schemaRestrictionResponseType = SchemaRestrictionResponse::class;
  protected $schemaRestrictionResponseDataType = '';
  public $timeZone;

  public function setCurrencyCode($currencyCode)
  {
    $this->currencyCode = $currencyCode;
  }
  public function getCurrencyCode()
  {
    return $this->currencyCode;
  }
  public function setDataLossFromOtherRow($dataLossFromOtherRow)
  {
    $this->dataLossFromOtherRow = $dataLossFromOtherRow;
  }
  public function getDataLossFromOtherRow()
  {
    return $this->dataLossFromOtherRow;
  }
  public function setEmptyReason($emptyReason)
  {
    $this->emptyReason = $emptyReason;
  }
  public function getEmptyReason()
  {
    return $this->emptyReason;
  }
  /**
   * @param SchemaRestrictionResponse
   */
  public function setSchemaRestrictionResponse(SchemaRestrictionResponse $schemaRestrictionResponse)
  {
    $this->schemaRestrictionResponse = $schemaRestrictionResponse;
  }
  /**
   * @return SchemaRestrictionResponse
   */
  public function getSchemaRestrictionResponse()
  {
    return $this->schemaRestrictionResponse;
  }
  public function setTimeZone($timeZone)
  {
    $this->timeZone = $timeZone;
  }
  public function getTimeZone()
  {
    return $this->timeZone;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ResponseMetaData::class, 'Google_Service_AnalyticsData_ResponseMetaData');
