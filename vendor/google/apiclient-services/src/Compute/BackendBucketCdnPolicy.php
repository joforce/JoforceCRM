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

class BackendBucketCdnPolicy extends \Google\Collection
{
  protected $collection_key = 'signedUrlKeyNames';
  protected $bypassCacheOnRequestHeadersType = BackendBucketCdnPolicyBypassCacheOnRequestHeader::class;
  protected $bypassCacheOnRequestHeadersDataType = 'array';
  public $cacheMode;
  public $clientTtl;
  public $defaultTtl;
  public $maxTtl;
  public $negativeCaching;
  protected $negativeCachingPolicyType = BackendBucketCdnPolicyNegativeCachingPolicy::class;
  protected $negativeCachingPolicyDataType = 'array';
  public $requestCoalescing;
  public $serveWhileStale;
  public $signedUrlCacheMaxAgeSec;
  public $signedUrlKeyNames;

  /**
   * @param BackendBucketCdnPolicyBypassCacheOnRequestHeader[]
   */
  public function setBypassCacheOnRequestHeaders($bypassCacheOnRequestHeaders)
  {
    $this->bypassCacheOnRequestHeaders = $bypassCacheOnRequestHeaders;
  }
  /**
   * @return BackendBucketCdnPolicyBypassCacheOnRequestHeader[]
   */
  public function getBypassCacheOnRequestHeaders()
  {
    return $this->bypassCacheOnRequestHeaders;
  }
  public function setCacheMode($cacheMode)
  {
    $this->cacheMode = $cacheMode;
  }
  public function getCacheMode()
  {
    return $this->cacheMode;
  }
  public function setClientTtl($clientTtl)
  {
    $this->clientTtl = $clientTtl;
  }
  public function getClientTtl()
  {
    return $this->clientTtl;
  }
  public function setDefaultTtl($defaultTtl)
  {
    $this->defaultTtl = $defaultTtl;
  }
  public function getDefaultTtl()
  {
    return $this->defaultTtl;
  }
  public function setMaxTtl($maxTtl)
  {
    $this->maxTtl = $maxTtl;
  }
  public function getMaxTtl()
  {
    return $this->maxTtl;
  }
  public function setNegativeCaching($negativeCaching)
  {
    $this->negativeCaching = $negativeCaching;
  }
  public function getNegativeCaching()
  {
    return $this->negativeCaching;
  }
  /**
   * @param BackendBucketCdnPolicyNegativeCachingPolicy[]
   */
  public function setNegativeCachingPolicy($negativeCachingPolicy)
  {
    $this->negativeCachingPolicy = $negativeCachingPolicy;
  }
  /**
   * @return BackendBucketCdnPolicyNegativeCachingPolicy[]
   */
  public function getNegativeCachingPolicy()
  {
    return $this->negativeCachingPolicy;
  }
  public function setRequestCoalescing($requestCoalescing)
  {
    $this->requestCoalescing = $requestCoalescing;
  }
  public function getRequestCoalescing()
  {
    return $this->requestCoalescing;
  }
  public function setServeWhileStale($serveWhileStale)
  {
    $this->serveWhileStale = $serveWhileStale;
  }
  public function getServeWhileStale()
  {
    return $this->serveWhileStale;
  }
  public function setSignedUrlCacheMaxAgeSec($signedUrlCacheMaxAgeSec)
  {
    $this->signedUrlCacheMaxAgeSec = $signedUrlCacheMaxAgeSec;
  }
  public function getSignedUrlCacheMaxAgeSec()
  {
    return $this->signedUrlCacheMaxAgeSec;
  }
  public function setSignedUrlKeyNames($signedUrlKeyNames)
  {
    $this->signedUrlKeyNames = $signedUrlKeyNames;
  }
  public function getSignedUrlKeyNames()
  {
    return $this->signedUrlKeyNames;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BackendBucketCdnPolicy::class, 'Google_Service_Compute_BackendBucketCdnPolicy');
