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

namespace Google\Service\CloudBillingBudget;

class GoogleCloudBillingBudgetsV1Filter extends \Google\Collection
{
  protected $collection_key = 'subaccounts';
  public $calendarPeriod;
  public $creditTypes;
  public $creditTypesTreatment;
  protected $customPeriodType = GoogleCloudBillingBudgetsV1CustomPeriod::class;
  protected $customPeriodDataType = '';
  public $labels;
  public $projects;
  public $services;
  public $subaccounts;

  public function setCalendarPeriod($calendarPeriod)
  {
    $this->calendarPeriod = $calendarPeriod;
  }
  public function getCalendarPeriod()
  {
    return $this->calendarPeriod;
  }
  public function setCreditTypes($creditTypes)
  {
    $this->creditTypes = $creditTypes;
  }
  public function getCreditTypes()
  {
    return $this->creditTypes;
  }
  public function setCreditTypesTreatment($creditTypesTreatment)
  {
    $this->creditTypesTreatment = $creditTypesTreatment;
  }
  public function getCreditTypesTreatment()
  {
    return $this->creditTypesTreatment;
  }
  /**
   * @param GoogleCloudBillingBudgetsV1CustomPeriod
   */
  public function setCustomPeriod(GoogleCloudBillingBudgetsV1CustomPeriod $customPeriod)
  {
    $this->customPeriod = $customPeriod;
  }
  /**
   * @return GoogleCloudBillingBudgetsV1CustomPeriod
   */
  public function getCustomPeriod()
  {
    return $this->customPeriod;
  }
  public function setLabels($labels)
  {
    $this->labels = $labels;
  }
  public function getLabels()
  {
    return $this->labels;
  }
  public function setProjects($projects)
  {
    $this->projects = $projects;
  }
  public function getProjects()
  {
    return $this->projects;
  }
  public function setServices($services)
  {
    $this->services = $services;
  }
  public function getServices()
  {
    return $this->services;
  }
  public function setSubaccounts($subaccounts)
  {
    $this->subaccounts = $subaccounts;
  }
  public function getSubaccounts()
  {
    return $this->subaccounts;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudBillingBudgetsV1Filter::class, 'Google_Service_CloudBillingBudget_GoogleCloudBillingBudgetsV1Filter');
