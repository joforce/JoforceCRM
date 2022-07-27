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

namespace Google\Service\Dialogflow;

class GoogleCloudDialogflowCxV3beta1Intent extends \Google\Collection
{
  protected $collection_key = 'trainingPhrases';
  public $description;
  public $displayName;
  public $isFallback;
  public $labels;
  public $name;
  protected $parametersType = GoogleCloudDialogflowCxV3beta1IntentParameter::class;
  protected $parametersDataType = 'array';
  public $priority;
  protected $trainingPhrasesType = GoogleCloudDialogflowCxV3beta1IntentTrainingPhrase::class;
  protected $trainingPhrasesDataType = 'array';

  public function setDescription($description)
  {
    $this->description = $description;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  public function getDisplayName()
  {
    return $this->displayName;
  }
  public function setIsFallback($isFallback)
  {
    $this->isFallback = $isFallback;
  }
  public function getIsFallback()
  {
    return $this->isFallback;
  }
  public function setLabels($labels)
  {
    $this->labels = $labels;
  }
  public function getLabels()
  {
    return $this->labels;
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
   * @param GoogleCloudDialogflowCxV3beta1IntentParameter[]
   */
  public function setParameters($parameters)
  {
    $this->parameters = $parameters;
  }
  /**
   * @return GoogleCloudDialogflowCxV3beta1IntentParameter[]
   */
  public function getParameters()
  {
    return $this->parameters;
  }
  public function setPriority($priority)
  {
    $this->priority = $priority;
  }
  public function getPriority()
  {
    return $this->priority;
  }
  /**
   * @param GoogleCloudDialogflowCxV3beta1IntentTrainingPhrase[]
   */
  public function setTrainingPhrases($trainingPhrases)
  {
    $this->trainingPhrases = $trainingPhrases;
  }
  /**
   * @return GoogleCloudDialogflowCxV3beta1IntentTrainingPhrase[]
   */
  public function getTrainingPhrases()
  {
    return $this->trainingPhrases;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3beta1Intent::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3beta1Intent');
