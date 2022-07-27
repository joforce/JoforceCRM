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

namespace Google\Service\DisplayVideo;

class DoubleVerifyDisplayViewability extends \Google\Model
{
  public $iab;
  public $viewableDuring;

  public function setIab($iab)
  {
    $this->iab = $iab;
  }
  public function getIab()
  {
    return $this->iab;
  }
  public function setViewableDuring($viewableDuring)
  {
    $this->viewableDuring = $viewableDuring;
  }
  public function getViewableDuring()
  {
    return $this->viewableDuring;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DoubleVerifyDisplayViewability::class, 'Google_Service_DisplayVideo_DoubleVerifyDisplayViewability');
