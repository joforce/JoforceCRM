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

namespace Google\Service\CustomSearchAPI;

class ResultImage extends \Google\Model
{
  public $byteSize;
  public $contextLink;
  public $height;
  public $thumbnailHeight;
  public $thumbnailLink;
  public $thumbnailWidth;
  public $width;

  public function setByteSize($byteSize)
  {
    $this->byteSize = $byteSize;
  }
  public function getByteSize()
  {
    return $this->byteSize;
  }
  public function setContextLink($contextLink)
  {
    $this->contextLink = $contextLink;
  }
  public function getContextLink()
  {
    return $this->contextLink;
  }
  public function setHeight($height)
  {
    $this->height = $height;
  }
  public function getHeight()
  {
    return $this->height;
  }
  public function setThumbnailHeight($thumbnailHeight)
  {
    $this->thumbnailHeight = $thumbnailHeight;
  }
  public function getThumbnailHeight()
  {
    return $this->thumbnailHeight;
  }
  public function setThumbnailLink($thumbnailLink)
  {
    $this->thumbnailLink = $thumbnailLink;
  }
  public function getThumbnailLink()
  {
    return $this->thumbnailLink;
  }
  public function setThumbnailWidth($thumbnailWidth)
  {
    $this->thumbnailWidth = $thumbnailWidth;
  }
  public function getThumbnailWidth()
  {
    return $this->thumbnailWidth;
  }
  public function setWidth($width)
  {
    $this->width = $width;
  }
  public function getWidth()
  {
    return $this->width;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ResultImage::class, 'Google_Service_CustomSearchAPI_ResultImage');
