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

namespace Google\Service\Sheets;

class PivotGroup extends \Google\Collection
{
  protected $collection_key = 'valueMetadata';
  protected $dataSourceColumnReferenceType = DataSourceColumnReference::class;
  protected $dataSourceColumnReferenceDataType = '';
  protected $groupLimitType = PivotGroupLimit::class;
  protected $groupLimitDataType = '';
  protected $groupRuleType = PivotGroupRule::class;
  protected $groupRuleDataType = '';
  public $label;
  public $repeatHeadings;
  public $showTotals;
  public $sortOrder;
  public $sourceColumnOffset;
  protected $valueBucketType = PivotGroupSortValueBucket::class;
  protected $valueBucketDataType = '';
  protected $valueMetadataType = PivotGroupValueMetadata::class;
  protected $valueMetadataDataType = 'array';

  /**
   * @param DataSourceColumnReference
   */
  public function setDataSourceColumnReference(DataSourceColumnReference $dataSourceColumnReference)
  {
    $this->dataSourceColumnReference = $dataSourceColumnReference;
  }
  /**
   * @return DataSourceColumnReference
   */
  public function getDataSourceColumnReference()
  {
    return $this->dataSourceColumnReference;
  }
  /**
   * @param PivotGroupLimit
   */
  public function setGroupLimit(PivotGroupLimit $groupLimit)
  {
    $this->groupLimit = $groupLimit;
  }
  /**
   * @return PivotGroupLimit
   */
  public function getGroupLimit()
  {
    return $this->groupLimit;
  }
  /**
   * @param PivotGroupRule
   */
  public function setGroupRule(PivotGroupRule $groupRule)
  {
    $this->groupRule = $groupRule;
  }
  /**
   * @return PivotGroupRule
   */
  public function getGroupRule()
  {
    return $this->groupRule;
  }
  public function setLabel($label)
  {
    $this->label = $label;
  }
  public function getLabel()
  {
    return $this->label;
  }
  public function setRepeatHeadings($repeatHeadings)
  {
    $this->repeatHeadings = $repeatHeadings;
  }
  public function getRepeatHeadings()
  {
    return $this->repeatHeadings;
  }
  public function setShowTotals($showTotals)
  {
    $this->showTotals = $showTotals;
  }
  public function getShowTotals()
  {
    return $this->showTotals;
  }
  public function setSortOrder($sortOrder)
  {
    $this->sortOrder = $sortOrder;
  }
  public function getSortOrder()
  {
    return $this->sortOrder;
  }
  public function setSourceColumnOffset($sourceColumnOffset)
  {
    $this->sourceColumnOffset = $sourceColumnOffset;
  }
  public function getSourceColumnOffset()
  {
    return $this->sourceColumnOffset;
  }
  /**
   * @param PivotGroupSortValueBucket
   */
  public function setValueBucket(PivotGroupSortValueBucket $valueBucket)
  {
    $this->valueBucket = $valueBucket;
  }
  /**
   * @return PivotGroupSortValueBucket
   */
  public function getValueBucket()
  {
    return $this->valueBucket;
  }
  /**
   * @param PivotGroupValueMetadata[]
   */
  public function setValueMetadata($valueMetadata)
  {
    $this->valueMetadata = $valueMetadata;
  }
  /**
   * @return PivotGroupValueMetadata[]
   */
  public function getValueMetadata()
  {
    return $this->valueMetadata;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PivotGroup::class, 'Google_Service_Sheets_PivotGroup');
