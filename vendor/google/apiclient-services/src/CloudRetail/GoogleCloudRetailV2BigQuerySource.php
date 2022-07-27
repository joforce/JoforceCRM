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

namespace Google\Service\CloudRetail;

class GoogleCloudRetailV2BigQuerySource extends \Google\Model
{
  public $dataSchema;
  public $datasetId;
  public $gcsStagingDir;
  protected $partitionDateType = GoogleTypeDate::class;
  protected $partitionDateDataType = '';
  public $projectId;
  public $tableId;

  public function setDataSchema($dataSchema)
  {
    $this->dataSchema = $dataSchema;
  }
  public function getDataSchema()
  {
    return $this->dataSchema;
  }
  public function setDatasetId($datasetId)
  {
    $this->datasetId = $datasetId;
  }
  public function getDatasetId()
  {
    return $this->datasetId;
  }
  public function setGcsStagingDir($gcsStagingDir)
  {
    $this->gcsStagingDir = $gcsStagingDir;
  }
  public function getGcsStagingDir()
  {
    return $this->gcsStagingDir;
  }
  /**
   * @param GoogleTypeDate
   */
  public function setPartitionDate(GoogleTypeDate $partitionDate)
  {
    $this->partitionDate = $partitionDate;
  }
  /**
   * @return GoogleTypeDate
   */
  public function getPartitionDate()
  {
    return $this->partitionDate;
  }
  public function setProjectId($projectId)
  {
    $this->projectId = $projectId;
  }
  public function getProjectId()
  {
    return $this->projectId;
  }
  public function setTableId($tableId)
  {
    $this->tableId = $tableId;
  }
  public function getTableId()
  {
    return $this->tableId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRetailV2BigQuerySource::class, 'Google_Service_CloudRetail_GoogleCloudRetailV2BigQuerySource');
