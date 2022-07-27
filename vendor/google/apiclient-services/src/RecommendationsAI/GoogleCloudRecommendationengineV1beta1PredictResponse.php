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

namespace Google\Service\RecommendationsAI;

class GoogleCloudRecommendationengineV1beta1PredictResponse extends \Google\Collection
{
  protected $collection_key = 'results';
  public $dryRun;
  public $itemsMissingInCatalog;
  public $metadata;
  public $nextPageToken;
  public $recommendationToken;
  protected $resultsType = GoogleCloudRecommendationengineV1beta1PredictResponsePredictionResult::class;
  protected $resultsDataType = 'array';

  public function setDryRun($dryRun)
  {
    $this->dryRun = $dryRun;
  }
  public function getDryRun()
  {
    return $this->dryRun;
  }
  public function setItemsMissingInCatalog($itemsMissingInCatalog)
  {
    $this->itemsMissingInCatalog = $itemsMissingInCatalog;
  }
  public function getItemsMissingInCatalog()
  {
    return $this->itemsMissingInCatalog;
  }
  public function setMetadata($metadata)
  {
    $this->metadata = $metadata;
  }
  public function getMetadata()
  {
    return $this->metadata;
  }
  public function setNextPageToken($nextPageToken)
  {
    $this->nextPageToken = $nextPageToken;
  }
  public function getNextPageToken()
  {
    return $this->nextPageToken;
  }
  public function setRecommendationToken($recommendationToken)
  {
    $this->recommendationToken = $recommendationToken;
  }
  public function getRecommendationToken()
  {
    return $this->recommendationToken;
  }
  /**
   * @param GoogleCloudRecommendationengineV1beta1PredictResponsePredictionResult[]
   */
  public function setResults($results)
  {
    $this->results = $results;
  }
  /**
   * @return GoogleCloudRecommendationengineV1beta1PredictResponsePredictionResult[]
   */
  public function getResults()
  {
    return $this->results;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRecommendationengineV1beta1PredictResponse::class, 'Google_Service_RecommendationsAI_GoogleCloudRecommendationengineV1beta1PredictResponse');
