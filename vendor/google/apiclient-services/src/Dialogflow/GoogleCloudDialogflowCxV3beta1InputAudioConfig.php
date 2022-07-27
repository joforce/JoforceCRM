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

class GoogleCloudDialogflowCxV3beta1InputAudioConfig extends \Google\Collection
{
  protected $collection_key = 'phraseHints';
  public $audioEncoding;
  public $enableWordInfo;
  public $model;
  public $modelVariant;
  public $phraseHints;
  public $sampleRateHertz;
  public $singleUtterance;

  public function setAudioEncoding($audioEncoding)
  {
    $this->audioEncoding = $audioEncoding;
  }
  public function getAudioEncoding()
  {
    return $this->audioEncoding;
  }
  public function setEnableWordInfo($enableWordInfo)
  {
    $this->enableWordInfo = $enableWordInfo;
  }
  public function getEnableWordInfo()
  {
    return $this->enableWordInfo;
  }
  public function setModel($model)
  {
    $this->model = $model;
  }
  public function getModel()
  {
    return $this->model;
  }
  public function setModelVariant($modelVariant)
  {
    $this->modelVariant = $modelVariant;
  }
  public function getModelVariant()
  {
    return $this->modelVariant;
  }
  public function setPhraseHints($phraseHints)
  {
    $this->phraseHints = $phraseHints;
  }
  public function getPhraseHints()
  {
    return $this->phraseHints;
  }
  public function setSampleRateHertz($sampleRateHertz)
  {
    $this->sampleRateHertz = $sampleRateHertz;
  }
  public function getSampleRateHertz()
  {
    return $this->sampleRateHertz;
  }
  public function setSingleUtterance($singleUtterance)
  {
    $this->singleUtterance = $singleUtterance;
  }
  public function getSingleUtterance()
  {
    return $this->singleUtterance;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3beta1InputAudioConfig::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3beta1InputAudioConfig');
